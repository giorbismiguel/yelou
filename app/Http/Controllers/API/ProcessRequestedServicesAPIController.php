<?php

namespace App\Http\Controllers\API;


use App\Repositories\RequestedServiceRepository;
use App\Repositories\RequestServicesRepository;
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

    public function __construct(
        RequestedServiceRepository $requestedServiceRepository,
        RequestServicesRepository $requestServiceRepository
    ) {
        $this->requestedServiceRepository = $requestedServiceRepository;
        $this->requestServiceRepository = $requestServiceRepository;
    }

    public function accept(Request $request)
    {
        if ($this->requestedServiceRepository->allQuery([
            'service_id' => $request->service_id,
            'status_id'  => 3, // Accept
        ])->exists()) {

            return $this->sendError('Esta solicitud ya fue antendida');
        }

        $service = $this->requestServiceRepository->find($request->service_id);
        $input = [
            'client_id'      => $service->user_id,
            'transporter_id' => $request->driver_id,
            'service_id'     => $request->service_id,
            'status_id'      => 1, // Pending
        ];

        if ($this->requestedServiceRepository->allQuery($input)->exists()) {

            return $this->sendError('Ya usted respondio a esta solicitud');
        }

        $requestedService = $this->requestedServiceRepository->create($input);

        return $this->sendResponse($requestedService->toArray(), 'Solicitud aceptada');
    }
}
