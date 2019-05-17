<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateTransportationStatesRequest;
use App\Http\Requests\Admin\UpdateTransportationStatesRequest;
use App\Repositories\Admin\TransportationStatesRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Response;

class TransportationStatesController extends AppBaseController
{
    /** @var  TransportationStatesRepository */
    private $transportationStatesRepository;

    public function __construct(TransportationStatesRepository $transportationStatesRepo)
    {
        $this->transportationStatesRepository = $transportationStatesRepo;
    }

    /**
     * Display a listing of the TransportationStates.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $transportationStates = $this->transportationStatesRepository->all();

        return view('admin.transportation_states.index')
            ->with('transportationStates', $transportationStates);
    }

    /**
     * Show the form for creating a new TransportationStates.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.transportation_states.create');
    }

    /**
     * Store a newly created TransportationStates in storage.
     *
     * @param CreateTransportationStatesRequest $request
     *
     * @return Response
     */
    public function store(CreateTransportationStatesRequest $request)
    {
        $input = $request->all();

        $transportationStates = $this->transportationStatesRepository->create($input);

        Flash::success('Transportation States saved successfully.');

        return redirect(route('admin.transportationStates.index'));
    }

    /**
     * Display the specified TransportationStates.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $transportationStates = $this->transportationStatesRepository->find($id);

        if (empty($transportationStates)) {
            Flash::error('Transportation States not found');

            return redirect(route('admin.transportationStates.index'));
        }

        return view('admin.transportation_states.show')->with('transportationStates', $transportationStates);
    }

    /**
     * Show the form for editing the specified TransportationStates.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $transportationStates = $this->transportationStatesRepository->find($id);

        if (empty($transportationStates)) {
            Flash::error('Transportation States not found');

            return redirect(route('admin.transportationStates.index'));
        }

        return view('admin.transportation_states.edit')->with('transportationStates', $transportationStates);
    }

    /**
     * Update the specified TransportationStates in storage.
     *
     * @param int $id
     * @param UpdateTransportationStatesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransportationStatesRequest $request)
    {
        $transportationStates = $this->transportationStatesRepository->find($id);

        if (empty($transportationStates)) {
            Flash::error('Transportation States not found');

            return redirect(route('admin.transportationStates.index'));
        }

        $transportationStates = $this->transportationStatesRepository->update($request->all(), $id);

        Flash::success('Transportation States updated successfully.');

        return redirect(route('admin.transportationStates.index'));
    }

    /**
     * Remove the specified TransportationStates from storage.
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        $transportationStates = $this->transportationStatesRepository->find($id);

        if (empty($transportationStates)) {
            Flash::error('Transportation States not found');

            return redirect(route('admin.transportationStates.index'));
        }

        $this->transportationStatesRepository->delete($id);

        Flash::success('Transportation States deleted successfully.');

        return redirect(route('admin.transportationStates.index'));
    }
}
