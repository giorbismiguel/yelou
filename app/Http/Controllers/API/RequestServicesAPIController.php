<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRequestServicesAPIRequest;
use App\Http\Requests\API\UpdateRequestServicesAPIRequest;
use App\Notifications\RequestServiceNotification;
use App\Repositories\RouteRepository;
use App\Repositories\TransportationAvailableRepository;
use App\Repositories\UserRepository;
use App\RequestServices;
use App\Repositories\RequestServicesRepository;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Notification;
use Response;

/**
 * Class RequestServicesController
 * @package App\Http\Controllers\API
 */
class RequestServicesAPIController extends AppBaseController
{
    /** @var  RequestServicesRepository */
    private $requestServicesRepository;
    private $transportationAvailableRepository;
    private $userRepository;
    private $routeRepository;

    public function __construct(
        RequestServicesRepository $requestServicesRepo,
        TransportationAvailableRepository $transportationAvailableRepo,
        UserRepository $userRepository,
        RouteRepository $routeRepository
    ) {
        $this->requestServicesRepository = $requestServicesRepo;
        $this->transportationAvailableRepository = $transportationAvailableRepo;
        $this->userRepository = $userRepository;
        $this->routeRepository = $routeRepository;
    }

    /**
     * Display a listing of the RequestServices.
     * GET|HEAD /requestServices
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $inputs = $request->merge(['user_id' => \Auth::id()])->except(['page', 'per_page', 'order_by', 'sort_by']);

        $requestServices = $this->requestServicesRepository->allPagination(
            $inputs,
            $request->get('page'),
            $request->get('per_page'),
            ['id', 'start_time', 'name_start', 'name_end', 'payment_method_id'],
            $request->get('order_by'),
            $request->get('sort_by')
        );

        return $this->sendResponsePagination($requestServices->toArray());
    }

    /**
     * @param CreateRequestServicesAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(CreateRequestServicesAPIRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = \Auth::id();

        if ($input['route_id']) {
            $this->routeRepository
                ->find($input['route_id'], ['id'])
                ->update([
                    'formatted_address_start' => $input['name_start'],
                    'lat_start'               => $input['lat_start'],
                    'lng_start'               => $input['lng_start'],
                    'formatted_address_end'   => $input['name_end'],
                    'lat_end'                 => $input['lat_end'],
                    'lng_end'                 => $input['lng_end'],
                ]);
        }

        $requestServices = $this->requestServicesRepository->create($input);
        $distanceToTravel = get_distance(
            $requestServices->lat_start, $requestServices->lng_start, $requestServices->lat_end,
            $requestServices->lng_end
        );

        $availableNerbyDrivers = $this->getDriversToSendNotification($input);

        $users = $this->userRepository->makeModel()->find($availableNerbyDrivers->toArray());

        Notification::send($users, new RequestServiceNotification($distanceToTravel));

        return $this->sendResponse($requestServices->toArray(), 'Solicitud del servicio enviada');
    }

    /**
     * Display the specified RequestServices.
     * GET|HEAD /requestServices/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RequestServices $requestServices */
        $requestServices = $this->requestServicesRepository->find($id);

        if (empty($requestServices)) {
            return $this->sendError('Request Services not found');
        }

        return $this->sendResponse($requestServices->toArray(), 'Request Services retrieved successfully');
    }

    /**
     * Update the specified RequestServices in storage.
     * PUT/PATCH /requestServices/{id}
     *
     * @param int                             $id
     * @param UpdateRequestServicesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRequestServicesAPIRequest $request)
    {
        $input = $request->all();

        /** @var RequestServices $requestServices */
        $requestServices = $this->requestServicesRepository->find($id);

        if (empty($requestServices)) {
            return $this->sendError('Request Services not found');
        }

        $requestServices = $this->requestServicesRepository->update($input, $id);

        return $this->sendResponse($requestServices->toArray(), 'RequestServices updated successfully');
    }

    /**
     * Remove the specified RequestServices from storage.
     * DELETE /requestServices/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RequestServices $requestServices */
        $requestServices = $this->requestServicesRepository->find($id);

        if (empty($requestServices)) {
            return $this->sendError('Request Services not found');
        }

        $requestServices->delete();

        return $this->sendResponse($id, 'Request Services deleted successfully');
    }

    /**
     * @param array $input
     * @return \Illuminate\Support\Collection
     */
    private function getDriversToSendNotification(array $input): \Illuminate\Support\Collection
    {
        $fields = ['lat', 'lng', 'user_id'];
        $availableDrivers = $this->transportationAvailableRepository->all(['active' => 1], null, null, $fields);
        $availableNerbyDrivers = $availableDrivers
            ->filter(function ($available) use ($input) {
                return get_distance(
                        $input['lat_start'],
                        $input['lng_start'], $available->lat,
                        $available->lng
                    ) < 10; // Km
            })
            ->pluck('user_id');
        return $availableNerbyDrivers;
    }
}
