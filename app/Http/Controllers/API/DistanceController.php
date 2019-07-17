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
        $price = 4; // From admin
        $result = [
            'distance' => get_distance(
                $request->latitude_from,
                $request->longitude_from,
                $request->latitude_to,
                $request->longitude_to
            ),
            'tariff'   => $price,
        ];

        $rate = format_money($result['distance'] / $price);

        return $this->sendResponse($result, 'La tarifa es: $'.$rate);
    }
}
