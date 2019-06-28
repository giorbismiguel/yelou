<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTransportationAvailableAPIRequest;
use App\Http\Requests\API\UpdateTransportationAvailableAPIRequest;
use App\TransportationAvailable;
use App\Repositories\TransportationAvailableRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Cache;
use Mockery\Exception;
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


    public function driversAvailable(Request $request)
    {
        $availables = Cache::remember('drivers-lists', 300, function () {
            return $this
                ->transportationAvailableRepository
                ->makeModel()
                ->where('active', '=', 1)
                ->with('user:id,first_name,last_name')
                ->get();
        });

        if (!$availables->count()) {
            return [];
        }

        $input = ['lat_start' => $request->get('lat'), 'lng_start' => $request->get('lng')];

        if ($input['lat_start'] && $input['lng_start']) {
            $availables = $availables
                ->filter(function ($available) use ($input) {
                    return get_distance(
                            $input['lat_start'],
                            $input['lng_start'],
                            $available->lat,
                            $available->lng
                        ) < 100000000000000000; // Km
                });
        }

        $drivers = $availables
            ->map(function ($available) {
                return [
                    'position' => [
                        'lat' => $available->lat,
                        'lng' => $available->lng
                    ],
                    'infoText' => $available->user->first_name.' '.$available->user->last_name,
                ];
            })
            ->toArray();

        return response()->success([
            'data' => $drivers,
        ]);
    }

    /**
     * @param CreateTransportationAvailableAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(CreateTransportationAvailableAPIRequest $request)
    {
        $input = $request->all();

        $transportationAvailable = $this->transportationAvailableRepository->makeModel()->updateOrInsert(
            ['user_id' => $input['user_id']],
            ['lat' => $input['lng'], 'lng' => $input['lng'], 'active' => $input['active']],
        );

        return $this->sendResponse(
            $transportationAvailable,
            'Punto de disponibilidad del transportista actualizado'
        );
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

        return $this->sendResponse($transportationAvailable->toArray(),
            'Punto de disponibilidad del transportista actualizado');
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
