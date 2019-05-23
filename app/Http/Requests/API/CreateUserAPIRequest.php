<?php

namespace App\Http\Requests\API;

use App\User;
use InfyOm\Generator\Request\APIRequest;

class CreateUserAPIRequest extends APIRequest
{

    const CLIENT = 1;

    const TRANSPORTATION = 2;

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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users',
            'password' => 'required|min:6|confirmed',
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'phone' => 'required|max:191|unique:users',
            'ruc' => 'required|max:191',
            'direction' => 'nullable|max:191',
        ];

        if ((int)request()->get('type') === self::CLIENT) {
            $rules += [
                'city' => 'required|max:191',
                'postal_code' => 'nullable|max:191',
            ];

        } elseif ((int)request()->get('type') === self::TRANSPORTATION) {
            $rules += [
                'license_types_id' => 'required|integer',
                'photo' => 'required|image|max:100000',
                'image_driver_license' => 'required|image|max:100000',
                'image_permit_circulation' => 'required|image|max:100000',
                'image_certificate_background' => 'nullable|image|max:100000',
            ];
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'name' => 'Nombre de usuario',
            'email' => 'Correo electrónico',
            'password' => 'Contraseña',
            'first_name' => 'Nombre ',
            'last_name' => 'Apellido ',
            'phone' => 'Teléfono',
            'ruc' => 'RUC',
            'direction' => 'Dirección',
            'city' => 'Ciudad',
            'postal_code' => 'Código Postal',
            'license_types_id' => 'Tipo de licencia de conducir',
            'photo' => 'Foto',
            'image_driver_license' => 'Foto de la licencia de conducir',
            'image_permit_circulation' => 'Foto del Permiso de circulación',
            'image_certificate_background' => 'Foto del Certificado de Antecedentes',
        ];
    }
}
