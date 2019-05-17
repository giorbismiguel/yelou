<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Requests\API\Admin\CreateTransportationStatesAPIRequest;
use App\Http\Requests\API\Admin\UpdateTransportationStatesAPIRequest;
use App\Models\Admin\TransportationStates;
use App\Repositories\Admin\TransportationStatesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class TransportationStatesController
 * @package App\Http\Controllers\API\Admin
 */

class TransportationStatesAPIController extends AppBaseController
{
    /** @var  TransportationStatesRepository */
    private $transportationStatesRepository;

    public function __construct(TransportationStatesRepository $transportationStatesRepo)
    {
        $this->transportationStatesRepository = $transportationStatesRepo;
    }

    /**
     * Display a listing of the TransportationStates.
     * GET|HEAD /transportationStates
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $transportationStates = $this->transportationStatesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($transportationStates->toArray(), 'Transportation States retrieved successfully');
    }

    /**
     * Store a newly created TransportationStates in storage.
     * POST /transportationStates
     *
     * @param CreateTransportationStatesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTransportationStatesAPIRequest $request)
    {
        $input = $request->all();

        $transportationStates = $this->transportationStatesRepository->create($input);

        return $this->sendResponse($transportationStates->toArray(), 'Transportation States saved successfully');
    }

    /**
     * Display the specified TransportationStates.
     * GET|HEAD /transportationStates/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TransportationStates $transportationStates */
        $transportationStates = $this->transportationStatesRepository->find($id);

        if (empty($transportationStates)) {
            return $this->sendError('Transportation States not found');
        }

        return $this->sendResponse($transportationStates->toArray(), 'Transportation States retrieved successfully');
    }

    /**
     * Update the specified TransportationStates in storage.
     * PUT/PATCH /transportationStates/{id}
     *
     * @param int $id
     * @param UpdateTransportationStatesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransportationStatesAPIRequest $request)
    {
        $input = $request->all();

        /** @var TransportationStates $transportationStates */
        $transportationStates = $this->transportationStatesRepository->find($id);

        if (empty($transportationStates)) {
            return $this->sendError('Transportation States not found');
        }

        $transportationStates = $this->transportationStatesRepository->update($input, $id);

        return $this->sendResponse($transportationStates->toArray(), 'TransportationStates updated successfully');
    }

    /**
     * Remove the specified TransportationStates from storage.
     * DELETE /transportationStates/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TransportationStates $transportationStates */
        $transportationStates = $this->transportationStatesRepository->find($id);

        if (empty($transportationStates)) {
            return $this->sendError('Transportation States not found');
        }

        $transportationStates->delete();

        return $this->sendResponse($id, 'Transportation States deleted successfully');
    }
}
