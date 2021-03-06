<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRegisterGpsAPIRequest;
use App\Http\Requests\API\UpdateRegisterGpsAPIRequest;
use App\RegisterGps;
use App\Repositories\RegisterGpsRepository;
use App\Repositories\TransportationAvailableRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class RegisterGpsController
 * @package App\Http\Controllers\API
 */
class RegisterGpsAPIController extends AppBaseController
{
    /** @var  RegisterGpsRepository */
    private $registerGpsRepository;

    /** @var TransportationAvailableRepository */
    private $transportationAvailableRepo;

    public function __construct(
        RegisterGpsRepository $registerGpsRepo,
        TransportationAvailableRepository $transportationAvailableRepo
    ) {
        $this->registerGpsRepository = $registerGpsRepo;
        $this->transportationAvailableRepo = $transportationAvailableRepo;
    }

    /**
     * Display a listing of the RegisterGps.
     * GET|HEAD /registerGps
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $registerGps = $this->registerGpsRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($registerGps->toArray(), 'Todos los registros de coordenadas existentes');
    }

    /**
     * Store a newly created RegisterGps in storage.
     * POST /registerGps
     *
     * @param CreateRegisterGpsAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(CreateRegisterGpsAPIRequest $request)
    {
        /** @var Collection $registersGps */
        $registersGps = collect([]);
        foreach ($request->coordinates as $coordinate) {
            $coordinate['registered_at'] = convert_us_date_to_db($coordinate['registered_at']);
            $registersGps->push($this->registerGpsRepository->create($coordinate));
        }

        if ($registersGps->isNotEmpty()) {
            $lastRegisterGps = $registersGps->last();
            $this->transportationAvailableRepo->makeModel()->updateOrInsert(
                ['user_id' => $lastRegisterGps['driver_id']],
                ['lat' => $lastRegisterGps['lat'], 'lng' => $lastRegisterGps['lng']]
            );
        }

        return $this->sendResponse($registersGps->toArray(), 'Se ha registrado correctamente la coordenada');
    }

    /**
     * Display the specified RegisterGps.
     * GET|HEAD /registerGps/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RegisterGps $registerGps */
        $registerGps = $this->registerGpsRepository->find($id);

        if (empty($registerGps)) {
            return $this->sendError('Registro de Coordenada no encontrado');
        }

        return $this->sendResponse($registerGps->toArray(), 'Se ha obtenido correctamente la coordenada');
    }

    /**
     * Update the specified RegisterGps in storage.
     * PUT/PATCH /registerGps/{id}
     *
     * @param int                         $id
     * @param UpdateRegisterGpsAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRegisterGpsAPIRequest $request)
    {
        $input = $request->all();

        /** @var RegisterGps $registerGps */
        $registerGps = $this->registerGpsRepository->find($id);

        if (empty($registerGps)) {
            return $this->sendError('Registro de Coordenada no encontrado');
        }

        $input['registered_at'] = convert_us_date_to_db($input['registered_at']);
        $registerGps = $this->registerGpsRepository->update($input, $id);

        return $this->sendResponse($registerGps->toArray(), 'Se ha actualizado el registro de la coordenada');
    }

    /**
     * Remove the specified RegisterGps from storage.
     * DELETE /registerGps/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RegisterGps $registerGps */
        $registerGps = $this->registerGpsRepository->find($id);

        if (empty($registerGps)) {
            return $this->sendError('Registro de Coordenada no encontrado');
        }

        $registerGps->delete();

        return $this->sendResponse($id, 'Se ha eliminado el registro de la coordenada');
    }
}
