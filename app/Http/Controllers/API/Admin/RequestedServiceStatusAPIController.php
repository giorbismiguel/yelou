<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\API\Admin\CreateRequestedServiceStatusAPIRequest;
use App\Http\Requests\API\Admin\UpdateRequestedServiceStatusAPIRequest;
use App\Models\Admin\RequestedServiceStatus;
use App\Repositories\Admin\RequestedServiceStatusRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class RequestedServiceStatusController
 * @package App\Http\Controllers\API\Admin
 */

class RequestedServiceStatusAPIController extends AppBaseController
{
    /** @var  RequestedServiceStatusRepository */
    private $requestedServiceStatusRepository;

    public function __construct(RequestedServiceStatusRepository $requestedServiceStatusRepo)
    {
        $this->requestedServiceStatusRepository = $requestedServiceStatusRepo;
    }

    /**
     * Display a listing of the RequestedServiceStatus.
     * GET|HEAD /requestedServiceStatuses
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $requestedServiceStatuses = $this->requestedServiceStatusRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($requestedServiceStatuses->toArray(), 'Requested Service Statuses retrieved successfully');
    }

    /**
     * Store a newly created RequestedServiceStatus in storage.
     * POST /requestedServiceStatuses
     *
     * @param CreateRequestedServiceStatusAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRequestedServiceStatusAPIRequest $request)
    {
        $input = $request->all();

        $requestedServiceStatus = $this->requestedServiceStatusRepository->create($input);

        return $this->sendResponse($requestedServiceStatus->toArray(), 'Requested Service Status saved successfully');
    }

    /**
     * Display the specified RequestedServiceStatus.
     * GET|HEAD /requestedServiceStatuses/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RequestedServiceStatus $requestedServiceStatus */
        $requestedServiceStatus = $this->requestedServiceStatusRepository->find($id);

        if (empty($requestedServiceStatus)) {
            return $this->sendError('Requested Service Status not found');
        }

        return $this->sendResponse($requestedServiceStatus->toArray(), 'Requested Service Status retrieved successfully');
    }

    /**
     * Update the specified RequestedServiceStatus in storage.
     * PUT/PATCH /requestedServiceStatuses/{id}
     *
     * @param int $id
     * @param UpdateRequestedServiceStatusAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRequestedServiceStatusAPIRequest $request)
    {
        $input = $request->all();

        /** @var RequestedServiceStatus $requestedServiceStatus */
        $requestedServiceStatus = $this->requestedServiceStatusRepository->find($id);

        if (empty($requestedServiceStatus)) {
            return $this->sendError('Requested Service Status not found');
        }

        $requestedServiceStatus = $this->requestedServiceStatusRepository->update($input, $id);

        return $this->sendResponse($requestedServiceStatus->toArray(), 'RequestedServiceStatus updated successfully');
    }

    /**
     * Remove the specified RequestedServiceStatus from storage.
     * DELETE /requestedServiceStatuses/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RequestedServiceStatus $requestedServiceStatus */
        $requestedServiceStatus = $this->requestedServiceStatusRepository->find($id);

        if (empty($requestedServiceStatus)) {
            return $this->sendError('Requested Service Status not found');
        }

        $requestedServiceStatus->delete();

        return $this->sendResponse($id, 'Requested Service Status deleted successfully');
    }
}
