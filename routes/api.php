<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// API Group Routes
Route::prefix('v1')->group(function () {

    /*
     * Guest area
     */
    Route::namespace('Auth')
        ->prefix('auth')
        ->group(function () {
            Route::post('login', 'AuthController@login');
            Route::post('register', 'AuthController@register');
        });

    Route::namespace('Auth')
        ->middleware('api')
        ->prefix('password')
        ->group(function () {
            Route::post('create', 'PasswordResetController@create');
            Route::get('find/{token}', 'PasswordResetController@find');
            Route::post('reset', 'PasswordResetController@reset');
        });

    /*
     * Authenticated area
     */
    Route::middleware('auth:api')
        ->group(function () {
            /*
             * Client Area
             */
            Route::resource('routes', 'RouteAPIController');

            /*
             * Transport Area
             */

            /*
             * Admin Area
             */
            Route::prefix('admin')->group(function () {
                Route::resource('transportation_states', 'Admin\TransportationStatesAPIController');
            });

            /*
             * Comun Area
             */
            Route::resource('license_types', 'LicenseTypesAPIController');
        });
});