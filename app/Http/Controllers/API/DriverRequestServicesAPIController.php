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
        $driverRequestServices = $this->requestServicesRepository->allRequestServices(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit'),
            ['*'],
            $request->get('order_by'),
            $request->get('sort_by')
        );

        return $this->sendResponse($driverRequestServices->toArray(), 'Driver Request Services retrieved successfully');
    }
}
