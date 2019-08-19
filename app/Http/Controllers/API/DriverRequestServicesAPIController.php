<?php

namespace App\Http\Controllers\API;

use App\Repositories\RequestServicesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class DriverRequestServicesController
 * @package App\Http\Controllers\API
 */
class DriverRequestServicesAPIController extends AppBaseController
{
    /** @var  RequestServicesRepository */
    private $requestServicesRepository;

    public function __construct(RequestServicesRepository $requestServicesRepository)
    {
        $this->requestServicesRepository = $requestServicesRepository;
    }

    /**
     * Display a listing of the DriverRequestServices.
     * GET|HEAD /driverRequestServices
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $columns = [
            'request_services.id',
            'request_services.name_start',
            'request_services.lat_start',
            'request_services.lng_start',
            'request_services.name_end',
            'request_services.lat_end',
            'request_services.lng_end',
            'request_services.start_date',
            'request_services.start_time',
            'request_services.payment_method_id',
            'requested_services.transporter_id',
            'request_services.route_id',
            'request_services.created_at',
        ];

        $driverRequestServices = $this->requestServicesRepository->allRequestServices(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            $columns,
            $request->get('order_by'),
            $request->get('sort_by')
        );

        return $this->sendResponse($driverRequestServices->toArray(), 'Todos los servicios disponibles');
    }
}
