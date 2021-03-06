<?php

namespace App\Http\Requests\API;

use App\RegisterGps;
use InfyOm\Generator\Request\APIRequest;

class UpdateRegisterGpsAPIRequest extends APIRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return RegisterGps::$rules;
    }

    public function attributes()
    {
        return [
            'lat'           => 'Latitud',
            'lng'           => 'Longitud',
            'driver_id'     => 'Identificador del Chofer',
            'service_id'    => 'Identificador del Servicio',
            'registered_at' => 'Hora de Registro',
        ];
    }
}
