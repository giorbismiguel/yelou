<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateLicenseTypesAPIRequest;
use App\Http\Requests\API\UpdateLicenseTypesAPIRequest;
use App\Models\LicenseTypes;
use App\Repositories\LicenseTypesRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class LicenseTypesController
 * @package App\Http\Controllers\API
 */

class LicenseTypesAPIController extends AppBaseController
{
    /** @var  LicenseTypesRepository */
    private $licenseTypesRepository;

    public function __construct(LicenseTypesRepository $licenseTypesRepo)
    {
        $this->licenseTypesRepository = $licenseTypesRepo;
    }

    /**
     * Display a listing of the LicenseTypes.
     * GET|HEAD /licenseTypes
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $licenseTypes = $this->licenseTypesRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($licenseTypes->toArray(), 'License Types retrieved successfully');
    }

    /**
     * Store a newly created LicenseTypes in storage.
     * POST /licenseTypes
     *
     * @param CreateLicenseTypesAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateLicenseTypesAPIRequest $request)
    {
        $input = $request->all();

        $licenseTypes = $this->licenseTypesRepository->create($input);

        return $this->sendResponse($licenseTypes->toArray(), 'License Types saved successfully');
    }

    /**
     * Display the specified LicenseTypes.
     * GET|HEAD /licenseTypes/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var LicenseTypes $licenseTypes */
        $licenseTypes = $this->licenseTypesRepository->find($id);

        if (empty($licenseTypes)) {
            return $this->sendError('License Types not found');
        }

        return $this->sendResponse($licenseTypes->toArray(), 'License Types retrieved successfully');
    }

    /**
     * Update the specified LicenseTypes in storage.
     * PUT/PATCH /licenseTypes/{id}
     *
     * @param int $id
     * @param UpdateLicenseTypesAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateLicenseTypesAPIRequest $request)
    {
        $input = $request->all();

        /** @var LicenseTypes $licenseTypes */
        $licenseTypes = $this->licenseTypesRepository->find($id);

        if (empty($licenseTypes)) {
            return $this->sendError('License Types not found');
        }

        $licenseTypes = $this->licenseTypesRepository->update($input, $id);

        return $this->sendResponse($licenseTypes->toArray(), 'LicenseTypes updated successfully');
    }

    /**
     * Remove the specified LicenseTypes from storage.
     * DELETE /licenseTypes/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var LicenseTypes $licenseTypes */
        $licenseTypes = $this->licenseTypesRepository->find($id);

        if (empty($licenseTypes)) {
            return $this->sendError('License Types not found');
        }

        $licenseTypes->delete();

        return $this->sendResponse($id, 'License Types deleted successfully');
    }
}
