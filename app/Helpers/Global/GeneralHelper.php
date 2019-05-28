<?php

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
