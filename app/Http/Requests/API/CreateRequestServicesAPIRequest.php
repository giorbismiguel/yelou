<?php

namespace App\Http\Requests\API;

use App\RequestServices;
use InfyOm\Generator\Request\APIRequest;

class CreateRequestServicesAPIRequest extends APIRequest
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
        return RequestServices::$rules;
    }

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            'name_start'        => 'Origen',
            'lat_start'         => 'Origen',
            'lng_start'         => 'Origen',
            'name_end'          => 'Destino',
            'lat_end'           => 'Origen',
            'lng_end'           => 'Origen',
            'start_time'        => 'Hora de Inicio',
            'route_id'          => 'Envío de Rutas',
            'payment_method_id' => 'Medio de pago'
        ];
    }

    /**
     * @return array
     */
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
            'start_time.after'   => 'El campo Hora de Inicio debe ser una fecha posterior a ahora.',
        ];
    }
}
