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
class RequestedServicesAPIController extends AppBaseController
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
        $service = $this->requestServiceRepository->find($request->service_id);

        $requestedService = $this->requestedServiceRepository->create([
            'client_id'      => $service->user_id,
            'transporter_id' => $request->driver_id,
            'route_id'       => $service->router_id,
            'status_id'      => 1,
        ]);

        return $this->sendResponse($requestedService->toArray(), 'Solicitud del servicio enviada');
    }
}
