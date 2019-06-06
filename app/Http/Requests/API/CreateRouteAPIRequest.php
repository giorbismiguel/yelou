<?php

namespace App\Http\Requests\API;

use App\Route;
use InfyOm\Generator\Request\APIRequest;

class CreateRouteAPIRequest extends APIRequest
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
        return Route::$rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'name'                    => 'Nombre',
            'lat_start'               => 'Origen',
            'lng_start'               => 'Origen',
            'formatted_address_start' => 'Origen',
            'lat_end'                 => 'Destino',
            'lng_end'                 => 'Destino',
            'formatted_address_end'   => 'Destino'
        ];
    }

    public function messages()
    {
        return [
            'lat_start.required' => 'Vuelva a seleccionar el origen, no se ha enviado correctamente',
            'lng_start.required' => 'Vuelva a seleccionar el origen, no se ha enviado correctamente',
            'lat_end.required'   => 'Vuelva a seleccionar el destino, no se ha enviado correctamente',
            'lng_end.required'   => 'Vuelva a seleccionar el destino, no se ha enviado correctamente',
            'lat_start.numeric'  => 'Vuelva a seleccionar el origen, no se ha enviado correctamente',
            'lng_start.numeric'  => 'Vuelva a seleccionar el origen, no se ha enviado correctamente',
            'lat_end.numeric'    => 'Vuelva a seleccionar el destino, no se ha enviado correctamente',
            'lng_end.numeric'    => 'Vuelva a seleccionar el destino, no se ha enviado correctamente',
        ];
    }
}
