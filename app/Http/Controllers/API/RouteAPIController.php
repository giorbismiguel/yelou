<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRouteAPIRequest;
use App\Http\Requests\API\UpdateRouteAPIRequest;
use App\Route;
use App\Repositories\RouteRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class RouteController
 * @package App\Http\Controllers\API
 */
class RouteAPIController extends AppBaseController
{
    /** @var  RouteRepository */
    private $routeRepository;

    public function __construct(RouteRepository $routeRepo)
    {
        $this->routeRepository = $routeRepo;
    }

    /**
     * Display a listing of the Route.
     * GET|HEAD /routes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $routes = $request->merge(['user_id' => \Auth::id()])->except(['page', 'per_page', 'order_by', 'sort_by']);

        $routes = $this->routeRepository->allPagination(
            $routes,
            $request->get('page'),
            $request->get('per_page'),
            ['id', 'name', 'formatted_address_start', 'formatted_address_end'],
            $request->get('order_by'),
            $request->get('sort_by')
        );

        return $this->sendResponsePagination($routes->toArray());
    }

    /**
     * Store a newly created Route in storage.
     * POST /routes
     *
     * @param CreateRouteAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRouteAPIRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = \Auth::id();

        $route = $this->routeRepository->create($input);

        return $this->sendResponse($route->toArray(), 'La ruta ha sido creada');
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show($id)
    {
        $this->authorize('view', $this->routeRepository->find($id));

        /** @var Route $route */
        $route = $this->routeRepository->find($id);

        if (empty($route)) {
            return $this->sendError('Ruta no encontrada');
        }

        return $this->sendResponse($route->toArray(), 'Ruta obtenida satisfactoriamente');
    }

    /**
     * Update the specified Route in storage.
     * PUT/PATCH /routes/{id}
     *
     * @param                       $id
     * @param UpdateRouteAPIRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update($id, UpdateRouteAPIRequest $request)
    {
        $this->authorize('update', $this->routeRepository->find($id));

        $input = $request->all();

        /** @var Route $route */
        $route = $this->routeRepository->find($id);

        if (empty($route)) {
            return $this->sendError('Ruta no encontrada');
        }

        $route = $this->routeRepository->update($input, $id);

        return $this->sendResponse($route->toArray(), 'La ruta ha sido actualizada');
    }

    /**
     * Remove the specified Route from storage.
     * DELETE /routes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $this->authorize('delete', $this->routeRepository->find($id));

        /** @var Route $route */
        $route = $this->routeRepository->find($id);

        if (empty($route)) {
            return $this->sendError('Ruta no encontrada');
        }

        $route->delete();

        return $this->sendResponse($id, 'La ruta ha sido eliminada');
    }
}
