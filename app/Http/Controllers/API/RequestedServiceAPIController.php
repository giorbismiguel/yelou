<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRequestedServiceAPIRequest;
use App\Http\Requests\API\UpdateRequestedServiceAPIRequest;
use App\RequestedService;
use App\Repositories\RequestedServiceRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class RequestedServiceController
 * @package App\Http\Controllers\API
 */

class RequestedServiceAPIController extends AppBaseController
{
    /** @var  RequestedServiceRepository */
    private $requestedServiceRepository;

    public function __construct(RequestedServiceRepository $requestedServiceRepo)
    {
        $this->requestedServiceRepository = $requestedServiceRepo;
    }

    /**
     * Display a listing of the RequestedService.
     * GET|HEAD /requestedServices
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $requestedServices = $this->requestedServiceRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($requestedServices->toArray(), 'Requested Services retrieved successfully');
    }

    /**
     * Store a newly created RequestedService in storage.
     * POST /requestedServices
     *
     * @param CreateRequestedServiceAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRequestedServiceAPIRequest $request)
    {
        $input = $request->all();

        $requestedService = $this->requestedServiceRepository->create($input);

        return $this->sendResponse($requestedService->toArray(), 'Requested Service saved successfully');
    }

    /**
     * Display the specified RequestedService.
     * GET|HEAD /requestedServices/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RequestedService $requestedService */
        $requestedService = $this->requestedServiceRepository->find($id);

        if (empty($requestedService)) {
            return $this->sendError('Requested Service not found');
        }

        return $this->sendResponse($requestedService->toArray(), 'Requested Service retrieved successfully');
    }

    /**
     * Update the specified RequestedService in storage.
     * PUT/PATCH /requestedServices/{id}
     *
     * @param int $id
     * @param UpdateRequestedServiceAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRequestedServiceAPIRequest $request)
    {
        $input = $request->all();

        /** @var RequestedService $requestedService */
        $requestedService = $this->requestedServiceRepository->find($id);

        if (empty($requestedService)) {
            return $this->sendError('Requested Service not found');
        }

        $requestedService = $this->requestedServiceRepository->update($input, $id);

        return $this->sendResponse($requestedService->toArray(), 'RequestedService updated successfully');
    }

    /**
     * Remove the specified RequestedService from storage.
     * DELETE /requestedServices/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RequestedService $requestedService */
        $requestedService = $this->requestedServiceRepository->find($id);

        if (empty($requestedService)) {
            return $this->sendError('Requested Service not found');
        }

        $requestedService->delete();

        return $this->sendResponse($id, 'Requested Service deleted successfully');
    }
}
