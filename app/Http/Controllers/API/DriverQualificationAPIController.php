<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateDriverQualificationAPIRequest;
use App\DriverQualification;
use App\Repositories\DriverQualificationRepository;
use App\Repositories\UserRepository;
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

    /** @var UserRepository */
    private $userRepository;

    public function __construct(DriverQualificationRepository $driverQualificationRepo, UserRepository $userRepository)
    {
        $this->driverQualificationRepository = $driverQualificationRepo;
        $this->userRepository = $userRepository;
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

        return $this->sendResponse(
            $driverQualifications->toArray(),
            'Driver Qualifications retrieved successfully'
        );
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

        $user = $this->userRepository->find($input['driver_id']);
        if ($user && $user->type === 1) {
            return $this->sendError('No puede calificar a un cliente');
        }

        $driverQualification = $this->driverQualificationRepository->allQuery(
            ['driver_id' => $input['driver_id']]
        )->first();

        if ($driverQualification) {
            $input['qualification'] = ($input['qualification'] + $driverQualification->qualification) / 2;

            $driverQualification = $this->driverQualificationRepository->update($input, $driverQualification->id);

            return $this->sendResponse(
                $driverQualification->toArray(),
                'La calificación del chofer ha sido actualizada'
            );
        }

        $driverQualification = $this->driverQualificationRepository->create($input);

        return $this->sendResponse(
            $driverQualification->toArray(),
            'La calificación del chofer ha sido creada'
        );
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
            return $this->sendError('Calificación del chofer no encontrada');
        }

        return $this->sendResponse($driverQualification->toArray(), 'Calificación del chofer');
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
            return $this->sendError('Calificación del chofer no encontrada');
        }

        $driverQualification->delete();

        return $this->sendResponse($id, 'Calificación del chofer eliminada');
    }

}
