<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRequestServicesAPIRequest;
use App\Http\Requests\API\UpdateRequestServicesAPIRequest;
use App\RequestServices;
use App\Repositories\RequestServicesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class RequestServicesController
 * @package App\Http\Controllers\API
 */

class RequestServicesAPIController extends AppBaseController
{
    /** @var  RequestServicesRepository */
    private $requestServicesRepository;

    public function __construct(RequestServicesRepository $requestServicesRepo)
    {
        $this->requestServicesRepository = $requestServicesRepo;
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
        $requestServices = $this->requestServicesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($requestServices->toArray(), 'Request Services retrieved successfully');
    }

    /**
     * Store a newly created RequestServices in storage.
     * POST /requestServices
     *
     * @param CreateRequestServicesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRequestServicesAPIRequest $request)
    {
        $input = $request->all();

        $requestServices = $this->requestServicesRepository->create($input);

        return $this->sendResponse($requestServices->toArray(), 'Solicitud del servicio creada');
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
     * @param int $id
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
}
