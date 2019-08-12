<?php

namespace App\Http\Controllers\API;

use App\Events\RequestedServicesAccepted;
use App\Events\RequestedServicesAcceptedByClient;
use App\Notifications\RequestedDriverAccepted;
use App\Notifications\RequestedDriverRejected;
use App\Repositories\RequestedServiceRepository;
use App\Repositories\RequestServicesRepository;
use App\Repositories\UserRepository;
use App\RequestedService;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

/**
 * Class RequestServicesController
 * @package App\Http\Controllers\API
 */
class ProcessRequestedServicesAPIController extends AppBaseController
{
    /** @var  RequestServicesRepository */
    private $requestedServiceRepository;

    /** @var RequestedServiceRepository */
    private $requestServiceRepository;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(
        RequestedServiceRepository $requestedServiceRepository,
        RequestServicesRepository $requestServiceRepository,
        UserRepository $userRepository
    ) {
        $this->requestedServiceRepository = $requestedServiceRepository;
        $this->requestServiceRepository = $requestServiceRepository;
        $this->userRepository = $userRepository;
    }

    public function accept(Request $request)
    {
        $service = $this->requestServiceRepository->find($request->service_id);

        if ($this->requestedServiceRepository->allQuery([
            'client_id'  => $service->user_id,
            'service_id' => $request->service_id,
            'status_id'  => 3, // Accept
        ])->exists()) {
            return $this->sendError('Esta solicitud ya fue aceptada por el cliente', 429);
        }

        if ($this->requestedServiceRepository->allQuery([
            'client_id'      => $service->user_id,
            'transporter_id' => $request->driver_id,
            'service_id'     => $request->service_id,
        ])->exists()) {
            return $this->sendError('Ya usted respondio a esta solicitud', 429);
        }

        $input = [
            'client_id'      => $service->user_id,
            'transporter_id' => $request->driver_id,
            'service_id'     => $request->service_id,
            'status_id'      => 1, // Pending
        ];

        $input['created_at'] = now();

        /** @var RequestedService $requestedService */
        $requestedService = $this->requestedServiceRepository->create($input);

        event(new RequestedServicesAccepted($requestedService));

        return $this->sendResponse($requestedService->toArray(), 'Su Solicitud ha sido aceptada');
    }

    public function acceptClient(Request $request)
    {
        /** @var RequestedService $requestedService */
        $requestedService = $this->requestedServiceRepository->find($request->requested_service_id);

        $requestedService->load('service.route');

        $requestedService->update(['status_id' => 2]); // Accept

        $this->sendNotificationRejectedDrivers($requestedService);

        $this->rejectOtherOffer($requestedService);

        $this->sendNotificationAcceptedDriver($requestedService);

        return $this->sendResponse($requestedService->toArray(), 'Solicitud aceptada por el cliente');
    }

    /**
     * @param RequestedService $requestedService
     */
    private function rejectOtherOffer(RequestedService $requestedService): void
    {
        $requestedService
            ->where('id', '!=', $requestedService->id)
            ->where('service_id', '=', $requestedService->service_id)
            ->update(['status_id' => 3]); // Reject
    }

    /**
     * @param RequestedService $requestedService
     */
    private function sendNotificationRejectedDrivers(RequestedService $requestedService): void
    {
        $driversIds = $requestedService
            ->select('transporter_id')
            ->where('id', '!=', $requestedService->id)
            ->where('service_id', '=', $requestedService->service_id)
            ->get();

        if ($driversIds->count() > 0) {
            $users = $this->userRepository->find($driversIds->pluck('transporter_id')->toArray());

            foreach ($users as $user) {
                $user->notify(new RequestedDriverRejected($requestedService));
            }
        }
    }

    /**
     * @param RequestedService $requestedService
     */
    private function sendNotificationAcceptedDriver(RequestedService $requestedService): void
    {
        $user = $this->userRepository->find($requestedService->transporter_id);
        event(new RequestedServicesAcceptedByClient($requestedService, $user->id));

         $user->notify(new RequestedDriverAccepted($requestedService));
    }
}
