<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateTransportationAvailableAPIRequest;
use App\Http\Requests\API\UpdateTransportationAvailableAPIRequest;
use App\Models\TransportationAvailable;
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

        return $this->sendResponse($transportationAvailables->toArray(), 'Transportation Availables retrieved successfully');
    }

    /**
     * Store a newly created TransportationAvailable in storage.
     * POST /transportationAvailables
     *
     * @param CreateTransportationAvailableAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTransportationAvailableAPIRequest $request)
    {
        $input = $request->all();

        $transportationAvailable = $this->transportationAvailableRepository->create($input);

        return $this->sendResponse($transportationAvailable->toArray(), 'Transportation Available saved successfully');
    }

    /**
     * Display the specified TransportationAvailable.
     * GET|HEAD /transportationAvailables/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TransportationAvailable $transportationAvailable */
        $transportationAvailable = $this->transportationAvailableRepository->find($id);

        if (empty($transportationAvailable)) {
            return $this->sendError('Transportation Available not found');
        }

        return $this->sendResponse($transportationAvailable->toArray(), 'Transportation Available retrieved successfully');
    }

    /**
     * Update the specified TransportationAvailable in storage.
     * PUT/PATCH /transportationAvailables/{id}
     *
     * @param int $id
     * @param UpdateTransportationAvailableAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransportationAvailableAPIRequest $request)
    {
        $input = $request->all();

        /** @var TransportationAvailable $transportationAvailable */
        $transportationAvailable = $this->transportationAvailableRepository->find($id);

        if (empty($transportationAvailable)) {
            return $this->sendError('Transportation Available not found');
        }

        $transportationAvailable = $this->transportationAvailableRepository->update($input, $id);

        return $this->sendResponse($transportationAvailable->toArray(), 'TransportationAvailable updated successfully');
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
        /** @var TransportationAvailable $transportationAvailable */
        $transportationAvailable = $this->transportationAvailableRepository->find($id);

        if (empty($transportationAvailable)) {
            return $this->sendError('Transportation Available not found');
        }

        $transportationAvailable->delete();

        return $this->sendResponse($id, 'Transportation Available deleted successfully');
    }
}
