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
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Notification;
use Response;

/**
 * Class RequestServicesController
 * @package App\Http\Controllers\API
 */
class RequestedServicesAPIController extends AppBaseController
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

    public function accept(Request $request)
    {
        return $this->sendResponse([$request->service_id, $request->driver_id], 'Solicitud del servicio enviada');
    }
}
