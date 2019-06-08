<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTransportationAvailableAPIRequest;
use App\Http\Requests\API\UpdateTransportationAvailableAPIRequest;
use App\TransportationAvailable;
use App\Repositories\TransportationAvailableRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class TransportationAvailableController
 * @package App\Http\Controllers\API
 */
class TransportationAvailableAPIController extends AppBaseController
{
    /** @var  TransportationAvailableRepository */
    private $transportationAvailableRepository;

    public function __construct(TransportationAvailableRepository $transportationAvailableRepo)
    {
        $this->transportationAvailableRepository = $transportationAvailableRepo;
    }

    /**
     * Display a listing of the TransportationAvailable.
     * GET|HEAD /transportationAvailables
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $transportationAvailables = $this->transportationAvailableRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse(
            $transportationAvailables->toArray(),
            'Puntos de los transportistas Obtenidos'
        );
    }

    /**
     * @param CreateTransportationAvailableAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateTransportationAvailableAPIRequest $request)
    {
        $input = $request->all();

        $transportationAvailable = $this->transportationAvailableRepository->create($input);

        return $this->sendResponse($transportationAvailable->toArray(), 'Punto de disponibilidad del transportista creado');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', $this->transportationAvailableRepository->find($id));

        /** @var TransportationAvailable $transportationAvailable */
        $transportationAvailable = $this->transportationAvailableRepository->find($id);

        if (empty($transportationAvailable)) {
            return $this->sendError('Punto de disponibilidad del transportista no encontrado');
        }

        return $this->sendResponse(
            $transportationAvailable->toArray(),
            'Punto de disponibilidad del transportista Obtenido satisfactoriamente'
        );
    }

    /**
     * @param                                         $id
     * @param UpdateTransportationAvailableAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($id, UpdateTransportationAvailableAPIRequest $request)
    {
        $this->authorize('update', $this->transportationAvailableRepository->find($id));

        $input = $request->all();

        /** @var TransportationAvailable $transportationAvailable */
        $transportationAvailable = $this->transportationAvailableRepository->find($id);

        if (empty($transportationAvailable)) {
            return $this->sendError('Punto de disponibilidad del transportista no encontrado');
        }

        $transportationAvailable = $this->transportationAvailableRepository->update($input, $id);

        return $this->sendResponse($transportationAvailable->toArray(), 'Punto de disponibilidad del transportista actualizado');
    }

    /**
     * Remove the specified TransportationAvailable from storage.
     * DELETE /transportationAvailables/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->authorize('forceDelete', $this->transportationAvailableRepository->find($id));

        /** @var TransportationAvailable $transportationAvailable */
        $transportationAvailable = $this->transportationAvailableRepository->find($id);

        if (empty($transportationAvailable)) {
            return $this->sendError('Punto de disponibilidad del transportista no encontrado');
        }

        $transportationAvailable->forceDelete();

        return $this->sendResponse($id, 'Punto de disponibilidad del transportista eliminado');
    }
}
