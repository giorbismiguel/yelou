<?php

use Carbon\Carbon;

if (!function_exists('app_name')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function app_name()
    {
        return config('app.name');
    }
}

if (!function_exists('get_type_user')) {
    /**
     * @param $user
     * @return \Illuminate\Support\Collection
     */
    function get_type_user($user)
    {
        if ($user['type'] === UserTypes::CLIENT) {
            return collect($user)->forget([
                'license_types_id',
                'photo',
                'image_driver_license',
                'image_permit_circulation',
                'image_certificate_background'
            ]);
        }

        return collect($user)->forget(['postal_code', 'city']);
    }
}

if (!function_exists('generate_code')) {
    function generate_code()
    {
        $permittedChars = '0123456789';

        return substr(str_shuffle($permittedChars), 0, 6);
    }
}

if (!function_exists('get_distance')) {
    function get_distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $unit = 'K')
    {
        // Calculate distance between latitude and longitude
        $theta = $longitudeFrom - $longitudeTo;
        $dist = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) + cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;

        // Convert unit and return distance
        $unit = strtoupper($unit);
        if ($unit == 'K') {
            return round($miles * 1.609344, 2);
        } elseif ($unit == 'M') {
            return round($miles * 1609.344, 2);
        } else {
            return round($miles, 2);
        }
    }
}

if (!function_exists('convert_us_date_to_db')) {
    function convert_us_date_to_db($date, string $format = 'd/m/Y H:i:s', string $newFormat = 'Y-m-d H:i:s'): string
    {
        return Carbon::createFromFormat($format, $date)->format($newFormat);
    }
}
