<?php

namespace App\Http\Requests\API;

use App\Exceptions\AuthenticationException;
use Illuminate\Validation\Rule;
use InfyOm\Generator\Request\APIRequest;

class UpdateUserAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     * @throws AuthenticationException
     */
    public function rules()
    {
        if (!$this->has('type')) {
            throw new AuthenticationException('Debe especificar el tipo: 1 es Cliente o 2 es Transportista');
        }

        $rules = [
            'name'       => 'required|max:191',
            'email'      => [
                'required',
                'email',
                'max:191',
                Rule::unique('users')->ignore((int) $this->route('user')),
            ],
            'first_name' => 'required|max:191',
            'last_name'  => 'required|max:191',
            'phone'      => [
                'required',
                'max:191',
                Rule::unique('users')->ignore((int) $this->route('user'))
            ],
            'ruc'        => [
                'required',
                'max:191',
                Rule::unique('users')->ignore((int) $this->route('user'))
            ],
            'direction'  => 'nullable|max:191',
        ];

        if ((int) $this->get('type') === \UserTypes::CLIENT) {
            $rules += [
                'city'        => 'required|max:191',
                'postal_code' => 'nullable|max:191',
            ];

        } elseif ((int) request()->get('type') === \UserTypes::TRANSPORTATION) {
            $rules += [
                'birth_date'                   => 'required|date_format:d/m/Y',
                'license_types_id'             => 'required|integer',
                'photo'                        => 'nullable|image|max:100000',
                'image_driver_license'         => 'nullable|image|max:100000',
                'image_permit_circulation'     => 'nullable|image|max:100000',
                'image_certificate_background' => 'nullable|image|max:100000',
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name'                         => 'Nombre de usuario',
            'email'                        => 'Correo electrónico',
            'first_name'                   => 'Nombre ',
            'last_name'                    => 'Apellido ',
            'birth_date'                   => 'Fecha de nacimiento',
            'phone'                        => 'Teléfono',
            'ruc'                          => 'RUC',
            'direction'                    => 'Dirección',
            'city'                         => 'Ciudad',
            'postal_code'                  => 'Código Postal',
            'license_types_id'             => 'Tipo de licencia de conducir',
            'photo'                        => 'Foto',
            'image_driver_license'         => 'Foto de la licencia de conducir',
            'image_permit_circulation'     => 'Foto del Permiso de circulación',
            'image_certificate_background' => 'Foto del Certificado de Antecedentes',
        ];
    }
}
