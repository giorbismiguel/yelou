<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateRequestedServiceStatusRequest;
use App\Http\Requests\Admin\UpdateRequestedServiceStatusRequest;
use App\Repositories\Admin\RequestedServiceStatusRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class RequestedServiceStatusController extends AppBaseController
{
    /** @var  RequestedServiceStatusRepository */
    private $requestedServiceStatusRepository;

    public function __construct(RequestedServiceStatusRepository $requestedServiceStatusRepo)
    {
        $this->requestedServiceStatusRepository = $requestedServiceStatusRepo;
    }

    /**
     * Display a listing of the RequestedServiceStatus.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $requestedServiceStatuses = $this->requestedServiceStatusRepository->all();

        return view('admin.requested_service_statuses.index')
            ->with('requestedServiceStatuses', $requestedServiceStatuses);
    }

    /**
     * Show the form for creating a new RequestedServiceStatus.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.requested_service_statuses.create');
    }

    /**
     * Store a newly created RequestedServiceStatus in storage.
     *
     * @param CreateRequestedServiceStatusRequest $request
     *
     * @return Response
     */
    public function store(CreateRequestedServiceStatusRequest $request)
    {
        $input = $request->all();

        $requestedServiceStatus = $this->requestedServiceStatusRepository->create($input);

        Flash::success('Requested Service Status saved successfully.');

        return redirect(route('admin.requestedServiceStatuses.index'));
    }

    /**
     * Display the specified RequestedServiceStatus.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $requestedServiceStatus = $this->requestedServiceStatusRepository->find($id);

        if (empty($requestedServiceStatus)) {
            Flash::error('Requested Service Status not found');

            return redirect(route('admin.requestedServiceStatuses.index'));
        }

        return view('admin.requested_service_statuses.show')->with('requestedServiceStatus', $requestedServiceStatus);
    }

    /**
     * Show the form for editing the specified RequestedServiceStatus.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $requestedServiceStatus = $this->requestedServiceStatusRepository->find($id);

        if (empty($requestedServiceStatus)) {
            Flash::error('Requested Service Status not found');

            return redirect(route('admin.requestedServiceStatuses.index'));
        }

        return view('admin.requested_service_statuses.edit')->with('requestedServiceStatus', $requestedServiceStatus);
    }

    /**
     * Update the specified RequestedServiceStatus in storage.
     *
     * @param int $id
     * @param UpdateRequestedServiceStatusRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRequestedServiceStatusRequest $request)
    {
        $requestedServiceStatus = $this->requestedServiceStatusRepository->find($id);

        if (empty($requestedServiceStatus)) {
            Flash::error('Requested Service Status not found');

            return redirect(route('admin.requestedServiceStatuses.index'));
        }

        $requestedServiceStatus = $this->requestedServiceStatusRepository->update($request->all(), $id);

        Flash::success('Requested Service Status updated successfully.');

        return redirect(route('admin.requestedServiceStatuses.index'));
    }

    /**
     * Remove the specified RequestedServiceStatus from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $requestedServiceStatus = $this->requestedServiceStatusRepository->find($id);

        if (empty($requestedServiceStatus)) {
            Flash::error('Requested Service Status not found');

            return redirect(route('admin.requestedServiceStatuses.index'));
        }

        $this->requestedServiceStatusRepository->delete($id);

        Flash::success('Requested Service Status deleted successfully.');

        return redirect(route('admin.requestedServiceStatuses.index'));
    }
}
