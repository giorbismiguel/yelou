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
        $user = \Auth::user();
        $inputs = $request->except([
            'page',
            'per_page',
            'order_by',
            'sort_by'
        ]);

        if ($user->type === 2) { // Driver
            $inputs['transporter_id'] = $user->id;
        } elseif ($user->type === 1) { // Client
            $inputs['client_id'] = $user->id;
            $inputs['status_id'] = 1; // Pendiente
        }

        $requestedServices = $this->requestedServiceRepository->allPagination(
            $inputs,
            $request->get('page'),
            $request->get('per_page'),
            ['*'],
            $request->get('order_by'),
            $request->get('sort_by')
        );

        return $this->sendResponsePagination($requestedServices->toArray());
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
            return $this->sendError('Recorrido no encontrado');
        }

        return $this->sendResponse($requestedService->toArray(), 'Recorrido encontrado satisfactoriamente');
    }

    /**
     * Update the specified RequestedService in storage.
     * PUT/PATCH /requestedServices/{id}
     *
     * @param int                              $id
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
            return $this->sendError('Recorrido no encontrado');
        }

        if ($request->has('start_at') && $input['start_at']) {
            $input['start_at'] = convert_us_date_to_db($input['start_at']);
            $input['updated_at'] = now();
            $requestedService->setStatusStarted();
        }

        if ($request->has('end_at') && $input['end_at']) {
            $input['end_at'] = convert_us_date_to_db($input['end_at']);
            $input['updated_at'] = now();
            $requestedService->setStatusCompleted();
        }

        $requestedService = $this->requestedServiceRepository->update($input, $id);

        $requestedService->load('service.paymentMethod');

        return $this->sendResponse($requestedService->toArray(), 'El recorrido ha sido actualizado');
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
            return $this->sendError('Recorrido no encontrado');
        }

        if ($requestedService->status_id === 1) { // 1 Pendiente
            return $this->sendError('No puede eliminar un recorrido con estado pendiente.');
        }

        $requestedService->delete();

        return $this->sendResponse($id, 'El recorrido ha sido eliminado.');
    }
}
