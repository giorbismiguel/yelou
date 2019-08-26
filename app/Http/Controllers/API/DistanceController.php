<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;

class DistanceController extends AppBaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateRate(Request $request)
    {
        $distance = get_distance(
            $request->latitude_from,
            $request->longitude_from,
            $request->latitude_to,
            $request->longitude_to
        );

        $tariff = get_tariff($distance);
        $result = [
            'distance' => $distance,
            'tariff'   => $tariff,
        ];

        return $this->sendResponse($result, 'La tarifa es: $'.$tariff);
    }
}
