<?php

namespace App\Http\Controllers\API;

use App\Events\ServiceRequested;
use App\Http\Requests\API\CreateRequestServicesAPIRequest;
use App\Http\Requests\API\UpdateRequestServicesAPIRequest;
use App\Notifications\RequestServiceNotification;
use App\Repositories\RouteRepository;
use App\Repositories\TransportationAvailableRepository;
use App\Repositories\UserRepository;
use App\RequestServices;
use App\Repositories\RequestServicesRepository;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\DB;
use Response;

/**
 * Class RequestServicesController
 * @package App\Http\Controllers\API
 */
class RequestServicesAPIController extends AppBaseController
{
    /** @var  RequestServicesRepository */
    private $requestServicesRepository;
    private $transportationAvailableRepository;
    private $userRepository;
    private $routeRepository;

    public function __construct(
        RequestServicesRepository $requestServicesRepo,
        TransportationAvailableRepository $transportationAvailableRepo,
        UserRepository $userRepository,
        RouteRepository $routeRepository
    ) {
        $this->requestServicesRepository = $requestServicesRepo;
        $this->transportationAvailableRepository = $transportationAvailableRepo;
        $this->userRepository = $userRepository;
        $this->routeRepository = $routeRepository;
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
        $inputs = $request->merge(['user_id' => \Auth::id()])->except(['page', 'per_page', 'order_by', 'sort_by']);

        $requestServices = $this->requestServicesRepository->allPagination(
            $inputs,
            $request->get('page'),
            $request->get('per_page'),
            ['id', 'start_date', 'start_time', 'name_start', 'name_end', 'payment_method_id'],
            $request->get('order_by'),
            $request->get('sort_by')
        );

        return $this->sendResponsePagination($requestServices->toArray());
    }

    /**
     * Create Request Services and send notification to the drivers available
     *
     * @param CreateRequestServicesAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(CreateRequestServicesAPIRequest $request)
    {
        DB::beginTransaction();

        try {
            $input = $request->all();
            $input['user_id'] = \Auth::id();

            $availableNerbyDrivers = $this->getDriversToSendNotification($input);
            if ($availableNerbyDrivers->count() === 0) {
                return $this->sendError('No se ha encontrado ningún chofer disponible para anteder su servicio.');
            }

            if ($input['start_date']) {
                $input['start_date'] = convert_us_date_to_db($input['start_date'].' '.'00:00:00');
            }

            if (isset($input['id']) && $input['id']) {
                $requestServices = $this->requestServicesRepository->find($input['id']);
                $this->sendNotificationToDriver($requestServices, $availableNerbyDrivers);

                return $this->sendResponse($requestServices->toArray(), 'Solicitud del servicio enviada');
            }

            if ($request->filled('route_id')) {
                if (!$this->routeRepository->allQuery([
                    'id'      => $input['route_id'],
                    'user_id' => $input['user_id'],
                ])->exists()) {
                    throw new AuthorizationException('No puede actualizar esta ruta, no es el propietario');
                }

                $this->routeRepository
                    ->find($input['route_id'], ['id'])
                    ->update([
                        'formatted_address_start' => $input['name_start'],
                        'lat_start'               => $input['lat_start'],
                        'lng_start'               => $input['lng_start'],
                        'formatted_address_end'   => $input['name_end'],
                        'lat_end'                 => $input['lat_end'],
                        'lng_end'                 => $input['lng_end'],
                    ]);
            } elseif ($request->get('favourite') === 1) {
                $route = $this->routeRepository->create([
                    'name'                    => 'Origen: '.$input['name_start'].' y Destino: '.$input['name_end'],
                    'formatted_address_start' => $input['name_start'],
                    'lat_start'               => $input['lat_start'],
                    'lng_start'               => $input['lng_start'],
                    'formatted_address_end'   => $input['name_end'],
                    'lat_end'                 => $input['lat_end'],
                    'lng_end'                 => $input['lng_end'],
                    'user_id'                 => $input['user_id'],
                    'favourite'               => 1,
                ]);

                $input['route_id'] = $route->id;
            }

            /** @var RequestServices $requestServices */
            $requestServices = $this->requestServicesRepository->create($input);

            $this->sendNotificationToDriver($requestServices, $availableNerbyDrivers);

            DB::commit();

            return $this->sendResponse($requestServices->toArray(), 'Solicitud del servicio enviada');
        } catch (BroadcastException $e) {
            DB::rollBack();

            return $this->sendError('No se ha podido enviar la solicitud.', 500);
        } catch (\Exception $e) {
            DB::rollBack();

            return $this->sendError('El servicio no se ha podido crear', 500);
        }
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
            return $this->sendError('Servicio no encontrado.', 422);
        }

        return $this->sendResponse($requestServices->toArray(), 'Servicio encontrado');
    }

    /**
     * Update the specified RequestServices in storage.
     * PUT/PATCH /requestServices/{id}
     *
     * @param int                             $id
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
            return $this->sendError('Servicio no encontrado', 422);
        }

        $requestServices = $this->requestServicesRepository->update($input, $id);

        return $this->sendResponse($requestServices->toArray(), 'Servicio actualizado');
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

        // TODO Tener en cuenta cuando se puede eliminar
        if (empty($requestServices)) {
            return $this->sendError('Servicio no encontrado', 422);
        }

        $requestServices->delete();

        return $this->sendResponse($id, 'Servicio eliminado.');
    }

    /**
     * @param array $input
     * @return \Illuminate\Support\Collection
     */
    private function getDriversToSendNotification(array $input): \Illuminate\Support\Collection
    {
        $fields = ['lat', 'lng', 'user_id'];
        $availableDrivers = $this->transportationAvailableRepository->all(['active' => 1], null, null, $fields);
        $availableNerbyDrivers = $availableDrivers
            ->filter(function ($available) use ($input) {
                return get_distance(
                        $input['lat_start'],
                        $input['lng_start'],
                        $available->lat,
                        $available->lng
                    ) < 100000000000000000; // Km
            })
            ->pluck('user_id');

        return $availableNerbyDrivers;
    }

    public function requestServices()
    {
        // TEst
    }

    /**
     * @param                                $requestServices
     * @param \Illuminate\Support\Collection $availableNerbyDrivers
     * @throws \Exception
     */
    private function sendNotificationToDriver(
        $requestServices,
        \Illuminate\Support\Collection $availableNerbyDrivers
    ): void {
        $distanceToTravel = get_distance(
            $requestServices->lat_start,
            $requestServices->lng_start,
            $requestServices->lat_end,
            $requestServices->lng_end
        );

        event(new ServiceRequested($requestServices, $distanceToTravel));

        $drivers = $this->userRepository->makeModel()->find($availableNerbyDrivers->toArray());

        $drivers->each(function ($driver) use ($distanceToTravel, $requestServices) {
            $driver->notify(new RequestServiceNotification($driver, $requestServices, $distanceToTravel));
        });
    }
}
