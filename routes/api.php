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
            Route::post('register', 'AuthController@register');
            Route::post('login', 'AuthController@login');
            Route::get('/sms/send/{to}', 'AuthController@send');
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

            /*
             * Transport Area
             */

            /*
             * Admin Area
             */
            Route::group(['prefix' => 'admin'], function () {
                Route::resource('transportation_states', 'Admin\TransportationStatesAPIController');
            });
        });

});
