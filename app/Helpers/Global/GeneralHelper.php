<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

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

if (!function_exists('format_money')) {
    function format_money($value)
    {
        return number_format($value, 2, '.', ',');
    }
}

if (!function_exists('get_tariff')) {
    function get_tariff($distance)
    {
        $price = 4; // From admin Cost for KM

        return format_money($distance * $price);
    }
}

if (!function_exists('calculate_time')) {
    /**
     * @param float $dist
     * @param float $speed
     * @return string
     */
    function calculate_time(float $dist, float $speed)
    {
        // Calculate time in seconds
        $second = ($dist * 1) / ($speed * 0.277777777777777777777777777777777777);

        // convert to hours, minutes, seconds
        $hour = (int)($second / 3600);
        $second = $second - ($hour * 3600);
        $minute = (int)($second / 60);
        $second = (int)($second - ($minute * 60));

        $hour = format_time($hour);
        $minute = format_time($minute);
        $second = format_time($second);

        return "$hour:$minute:$second";
    }
}

if (!function_exists('format_time')) {
    /**
     * @param int $number
     * @return int|string
     */
    function format_time(int $number)
    {
        if ($number < 9) {
            return '0'.$number;
        }

        return $number;
    }
}


function reports_view_pdf($view, array $data = [], $returnView = false)
{
    $viewPath = 'reports.'.$view;

    if ($returnView) {
        return response()->view($viewPath, $data);
    }

    $view = View::make($viewPath, $data)->render();
    $view = preg_replace('/>\s+</', '><', $view);

    $pdf = App::make('dompdf.wrapper');
    $pdf->loadHTML($view);

    return $pdf;
}

function reports_view($view, $data)
{
    return reports_view_pdf($view, $data)
        ->stream();
}

function reports_view_landscape($view, $data)
{
    return reports_view_pdf($view, $data)
        ->setPaper('a4', 'landscape')
        ->stream();
}

function reports_view_portrait($view, $data)
{
    return reports_view_pdf($view, $data)
        ->setPaper('a4', 'portrait')
        ->stream();
}
