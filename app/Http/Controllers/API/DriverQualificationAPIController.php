<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDriverQualificationAPIRequest;
use App\Http\Requests\API\UpdateDriverQualificationAPIRequest;
use App\Models\DriverQualification;
use App\Repositories\DriverQualificationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class DriverQualificationController
 * @package App\Http\Controllers\API
 */

class DriverQualificationAPIController extends AppBaseController
{
    /** @var  DriverQualificationRepository */
    private $driverQualificationRepository;

    public function __construct(DriverQualificationRepository $driverQualificationRepo)
    {
        $this->driverQualificationRepository = $driverQualificationRepo;
    }

    /**
     * Display a listing of the DriverQualification.
     * GET|HEAD /driverQualifications
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $driverQualifications = $this->driverQualificationRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($driverQualifications->toArray(), 'Driver Qualifications retrieved successfully');
    }

    /**
     * Store a newly created DriverQualification in storage.
     * POST /driverQualifications
     *
     * @param CreateDriverQualificationAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDriverQualificationAPIRequest $request)
    {
        $input = $request->all();

        $driverQualification = $this->driverQualificationRepository->create($input);

        return $this->sendResponse($driverQualification->toArray(), 'Driver Qualification saved successfully');
    }

    /**
     * Display the specified DriverQualification.
     * GET|HEAD /driverQualifications/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var DriverQualification $driverQualification */
        $driverQualification = $this->driverQualificationRepository->find($id);

        if (empty($driverQualification)) {
            return $this->sendError('Driver Qualification not found');
        }

        return $this->sendResponse($driverQualification->toArray(), 'Driver Qualification retrieved successfully');
    }

    /**
     * Update the specified DriverQualification in storage.
     * PUT/PATCH /driverQualifications/{id}
     *
     * @param int $id
     * @param UpdateDriverQualificationAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDriverQualificationAPIRequest $request)
    {
        $input = $request->all();

        /** @var DriverQualification $driverQualification */
        $driverQualification = $this->driverQualificationRepository->find($id);

        if (empty($driverQualification)) {
            return $this->sendError('Driver Qualification not found');
        }

        $driverQualification = $this->driverQualificationRepository->update($input, $id);

        return $this->sendResponse($driverQualification->toArray(), 'DriverQualification updated successfully');
    }

    /**
     * Remove the specified DriverQualification from storage.
     * DELETE /driverQualifications/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var DriverQualification $driverQualification */
        $driverQualification = $this->driverQualificationRepository->find($id);

        if (empty($driverQualification)) {
            return $this->sendError('Driver Qualification not found');
        }

        $driverQualification->delete();

        return $this->sendResponse($id, 'Driver Qualification deleted successfully');
    }
}
