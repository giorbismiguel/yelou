<?php

namespace App\Http\Controllers\API\Auth;

use App\Exceptions\AuthenticationException;
use App\Http\Controllers\Controller;
use App\Notifications\UserRegistered;
use App\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Lang;
use Nexmo\Laravel\Facade\Nexmo;
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

            if (!$user->phoneVerified()->exists()) {
                return ['user' => null, 'access_token' => null, 'phone_verify' => false, 'phone' => $user->phone];
            }

            return ['user' => $user, 'access_token' => $user->makeApiToken(), 'phone_verify' => true];
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        abort(401, Lang::get('auth.failed'));
    }

    /**
     * Handle a registration request for the application.
     *
     * @param Request $request
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        try {
            if (!request()->has('type')) {
                throw new AuthenticationException('Debe especificar el tipo: 1 es Cliente o 2 es Transportista');
            }
        } catch (AuthenticationException $e) {
            return response()->success($e->getMessage());
        }

        $this->validator($request->all())->validate();

        $request = get_file($request, 'photo');
        $request = get_file($request, 'image_driver_license');
        $request = get_file($request, 'image_permit_circulation');
        $request = get_file($request, 'image_certificate_background');

        $codeActivation = generate_code();
        $request = $request->merge(['code_activation' => $codeActivation]);
        event(new Registered($user = $this->create($request->all())));

        $user->notify(new UserRegistered($codeActivation));

        Nexmo::message()->send([
            'to'   => $request->get('phone'),
            'from' => 'YElOU',
            'text' => __('app.message_code_activation', ['code' => $codeActivation])
        ]);

        return ['user' => $user];
    }

    /**
     * @param Request $request
     * @return array
     * @throws AuthenticationException
     */
    public function active(Request $request)
    {
        $query = User::where('code_activation', '=', $request->code_activation)
            ->where('phone', '=', $request->phone);

        if (!$query->exists()) {
            throw new AuthenticationException('Código de activación inválido');
        }

        $user = $query->first();
        /** @var User $user */
        $user->setVerifiedAt();

        return ['active' => true];
    }

    public function newActivationCode(Request $request)
    {
        $query = User::where('phone', '=', $request->phone);

        if (!$query->exists()) {
            throw new AuthenticationException('Número de teléfono inexistente');
        }

        $codeActivation = generate_code();
        $user = $query->first();
        $user->update(['code_activation' => $codeActivation]);

        Nexmo::message()->send([
            'to'   => $request->get('phone'),
            'from' => 'YElOU',
            'text' => __('app.message_code_activation', ['code' => $codeActivation])
        ]);

        return ['message_send' => true];
    }

    /**
     * @param array $data
     * @return \Illuminate\Validation\Validator
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

        if ((int) request()->get('type') === \UserTypes::CLIENT) {
            $rules += [
                'city'        => 'required|max:191',
                'postal_code' => 'nullable|max:191',
            ];

            $customAttributes += [
                'city'        => 'Ciudad',
                'postal_code' => 'Código Postal',
            ];
        } elseif ((int) request()->get('type') === \UserTypes::TRANSPORTATION) {
            $rules += [
                'license_types_id'             => 'required|integer',
                'photo'                        => 'required|image|max:100000',
                'image_driver_license'         => 'required|image|max:100000',
                'image_permit_circulation'     => 'required|image|max:100000',
                'image_certificate_background' => 'nullable|image|max:100000',
            ];

            $customAttributes += [
                'license_types_id'             => 'Tipo de licencia de conducir',
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
            'type'                         => $data['type'],
            'name'                         => $data['name'],
            'email'                        => $data['email'],
            'password'                     => bcrypt($data['password']),
            'first_name'                   => $data['first_name'],
            'last_name'                    => $data['last_name'],
            'phone'                        => $data['phone'],
            'ruc'                          => $data['ruc'],
            'direction'                    => $data['direction'],
            'code_activation'              => $data['code_activation'] ?? null,
            'city'                         => $data['city'] ?? null,
            'postal_code'                  => $data['postal_code'] ?? null,
            'license_types_id'             => $data['license_types_id'] ?? null,
            'photo'                        => $data['photo_name'] ?? null,
            'image_driver_license'         => $data['image_driver_license_name'] ?? null,
            'image_permit_circulation'     => $data['image_permit_circulation_name'] ?? null,
            'image_certificate_background' => $data['image_certificate_background_name'] ?? null,
        ]);
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
}
