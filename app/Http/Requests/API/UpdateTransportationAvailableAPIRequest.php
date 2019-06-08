<?php

namespace App\Http\Requests\API;

use App\TransportationAvailable;
use Illuminate\Validation\Rule;
use InfyOm\Generator\Request\APIRequest;

class UpdateTransportationAvailableAPIRequest extends APIRequest
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
        $rules = TransportationAvailable::$rules;

        $rules['user_id'] = [
            'required',
            'integer',
            Rule::unique('transportation_availables', 'user_id')->ignore((int) $this->route('transportation_available')),
        ];

        return $rules;
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

    public function messages()
    {
        return [
            'user_id.unique' => 'El transportista ya tiene punto de disponibilidad',
        ];
    }


}
