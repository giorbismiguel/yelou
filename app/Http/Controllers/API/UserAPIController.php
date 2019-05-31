<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateUserAPIRequest;
use App\Http\Requests\API\UpdateUserAPIRequest;
use App\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use Response;

/**
 * Class UserController
 * @package App\Http\Controllers\API
 */
class UserAPIController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     * GET|HEAD /users
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $users = $this->userRepository->all(
            $request->except(['skip', 'limit']),
            $request->get('skip'),
            $request->get('limit')
        );

        return $this->sendResponse($users->toArray(), 'Usuarios recuperados exitosamente');
    }

    /**
     * Store a newly created User in storage.
     * POST /users
     *
     * @param CreateUserAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateUserAPIRequest $request)
    {
        if ($request->get('type') === \UserTypes::TRANSPORTATION) {
            $request = get_file($request, 'photo');
            $request = get_file($request, 'image_driver_license');
            $request = get_file($request, 'image_permit_circulation');
            $request = get_file($request, 'image_certificate_background');
        }

        $input = $request->except(['password', 'password_confirmation']);

        $user = $this->userRepository->create($input);

        return $this->sendResponse(get_type_user($user->toArray()), 'Usuario guardado exitosamente');
    }

    /**
     * Display the specified User.
     * GET|HEAD /users/{id}
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('User not found');
        }

        return $this->sendResponse(get_type_user($user->toArray()), 'Usuario recuperado exitosamente');
    }

    /**
     * Update the specified User in storage.
     * PUT/PATCH /users/{id}
     *
     * @param int                  $id
     * @param UpdateUserAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserAPIRequest $request)
    {
        $request = get_file($request, 'photo');
        $request = get_file($request, 'image_driver_license');
        $request = get_file($request, 'image_permit_circulation');
        $request = get_file($request, 'image_certificate_background');

        $input = $request->except(['password', 'password_confirmation']);

        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('Usuario no encontrado');
        }

        $user = $this->userRepository->update($input, $id);

        return $this->sendResponse(['user' => get_type_user($user->toArray())], 'Usuario actualizado exitosamente');
    }

    /**
     * Remove the specified User from storage.
     * DELETE /users/{id}
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var User $user */
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            return $this->sendError('Usuario no encontrado');
        }

        $user->delete();

        return $this->sendResponse($id, 'El usuario fue eliminado exitosamente');
    }

    /**
     * Сurrent authenticated user
     *
     * Return current authenticated user data
     *
     * @param Request $request
     * @return mixed
     */
    public function me(Request $request)
    {
        return $request->user();
    }
}
