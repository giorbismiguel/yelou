<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Lang;
use Validator;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers
 * @resource Authentication
 */
class AuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @param Request $request
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            $seconds = $this->limiter()->availableIn($this->throttleKey($request));

            abort(429, Lang::get('auth.throttle', ['seconds' => $seconds]));
        }

        if ($this->attemptLogin($request)) {
            $this->clearLoginAttempts($request);

            /** @var User $user */
            $user = $this->guard()->user();

            return ['user' => $user];
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        abort(401, Lang::get('auth.failed'));
    }

    /**
     * Registration
     *
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $request = $this->getFile($request, 'photo');
        $request = $this->getFile($request, 'image_driver_license');
        $request = $this->getFile($request, 'image_permit_circulation');
        $request = $this->getFile($request, 'image_certificate_background');

        event(new Registered($user = $this->create($request->all())));

        return ['user' => $user, 'access_token' => $user->makeApiToken()];
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name'       => 'required|max:191',
            'email'      => 'required|email|max:191|unique:users',
            'password'   => 'required|min:6|confirmed',
            'first_name' => 'required|max:191',
            'last_name'  => 'required|max:191',
            'phone'      => 'required|max:191|unique:users',
            'ruc'        => 'required|max:191',
            'direction'  => 'nullable|max:191',
        ];

        $customAttributes = [
            'name'       => 'Nombre de usuario',
            'email'      => 'Correo electrónico',
            'password'   => 'Contraseña',
            'first_name' => 'Nombre ',
            'last_name'  => 'Apellido ',
            'phone'      => 'Teléfono',
            'ruc'        => 'RUC',
            'direction'  => 'Dirección',
        ];

        if (request()->get('postal_code') && request()->get('city')) {
            $rules += [
                'city'        => 'required|max:191',
                'postal_code' => 'nullable|max:191',
            ];

            $customAttributes += [
                'city'        => 'Ciudad',
                'postal_code' => 'Código Postal',
            ];
        } elseif (
            request()->get('type_driver_license') &&
            request()->get('photo') &&
            request()->get('image_driver_license') &&
            request()->get('image_permit_circulation') &&
            request()->get('image_certificate_background')
        ) {
            $rules += [
                'type_driver_license'          => 'required|integer',
                'photo'                        => 'required|image|max:100000',
                'image_driver_license'         => 'required|image|max:100000',
                'image_permit_circulation'     => 'required|image|max:100000',
                'image_certificate_background' => 'nullable|image|max:100000',
            ];

            $customAttributes += [
                'type_driver_license'          => 'Tipo de licencia de conducir',
                'photo'                        => 'Foto',
                'image_driver_license'         => 'Foto de la licencia de conducir',
                'image_permit_circulation'     => 'Foto del Permiso de circulación',
                'image_certificate_background' => 'Foto del Certificado de Antecedentes',
            ];
        }

        return Validator::make($data, $rules, [], $customAttributes);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'                         => $data['name'],
            'email'                        => $data['email'],
            'password'                     => bcrypt($data['password']),
            'first_name'                   => $data['first_name'],
            'last_name'                    => $data['last_name'],
            'phone'                        => $data['phone'],
            'ruc'                          => $data['ruc'],
            'direction'                    => $data['direction'],
            'city'                         => $data['city'] ?? null,
            'postal_code'                  => $data['postal_code'] ?? null,
            'type_driver_license'          => $data['type_driver_license'] ?? null,
            'photo'                        => $data['photo_name'] ?? null,
            'image_driver_license'         => $data['image_driver_license_name'] ?? null,
            'image_permit_circulation'     => $data['image_permit_circulation_name'] ?? null,
            'image_certificate_background' => $data['image_certificate_background_name'] ?? null,
        ]);
    }

    /**
     * @param Request $request
     * @param string  $nameField
     * @return Request
     */
    private function getFile(Request $request, string $nameField): Request
    {
        if ($request->hasFile($nameField) && $request->file($nameField)->isValid()) {
            // Get image file
            $image = $request->file($nameField);
            $name = strtolower(uniqid($request->input('name').'_img_', true));
            $fileName = $name.'.'.$image->getClientOriginalExtension();
            $path = $request->file($nameField)->storeAs('photos', $fileName);
            $request->merge([$nameField.'_name' => $path]);
        }

        return $request;
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        if (is_numeric($request->get('email'))) { // Verify if is phone
            return ['phone' => $request->get('email'), 'password' => $request->get('password')];
        } elseif (!filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)) {
            return ['name' => $request->get('email'), 'password' => $request->get('password')];
        }

        return $request->only($this->username(), 'password');
    }

    protected function send(\Nexmo\Client $nexmo, $to)
    {
        $message = $nexmo->message()->send([
            'to'   => $to,
            'from' => env('NEXMO_NUMBER'),
            'text' => 'Sending SMS from Laravel. Woohoo!'
        ]);

        Log::info('sent message: '.$message['message-id']);

        /** @var User $user */
        $user = $this->guard()->user();

        return ['access_token' => $user->makeApiToken()];
    }
}
