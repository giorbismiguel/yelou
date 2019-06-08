<?php

namespace App\Http\Requests\API;

use App\TransportationAvailable;
use InfyOm\Generator\Request\APIRequest;

class CreateTransportationAvailableAPIRequest extends APIRequest
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
        return TransportationAvailable::$rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'lat'     => 'Latitud',
            'lng'     => 'Longitud',
            'active'  => 'Activado',
            'user_id' => 'Identificador de usuario',
        ];
    }
}
