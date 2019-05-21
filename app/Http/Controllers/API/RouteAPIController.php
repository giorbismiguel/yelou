<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateRouteAPIRequest;
use App\Http\Requests\API\UpdateRouteAPIRequest;
use App\Models\Route;
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
        $routes = $this->routeRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($routes->toArray(), 'Rutas obtenidas satisfactoriamente');
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

        $route = $this->routeRepository->create($input);

        return $this->sendResponse($route->toArray(), 'La ruta ha sido actualizada');
    }

    /**
     * Display the specified Route.
     * GET|HEAD /routes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
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
     * @param int $id
     * @param UpdateRouteAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRouteAPIRequest $request)
    {
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
        /** @var Route $route */
        $route = $this->routeRepository->find($id);

        if (empty($route)) {
            return $this->sendError('Ruta no encontrada');
        }

        $route->delete();

        return $this->sendResponse($id, 'La ruta ha sido eliminada');
    }
}
