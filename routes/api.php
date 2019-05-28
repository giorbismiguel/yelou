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

// API Group Routes
Route::prefix('v1')->group(function () {

    /*
     * Guest area
     */
    Route::namespace('Auth')
        ->prefix('auth')
        ->group(function () {
            Route::post('/login', 'AuthController@login');
            Route::post('/register', 'AuthController@register');
            Route::post('/active', 'AuthController@active');
            Route::post('/new_activation_code', 'AuthController@newActivationCode');
        });

    Route::namespace('Auth')
        ->prefix('password')
        ->group(function () {
            Route::post('/create', 'PasswordResetController@create');
            Route::get('/find/{token}', 'PasswordResetController@find');
            Route::post('/reset', 'PasswordResetController@reset');
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
             * Area
             */
            Route::resource('users', 'UserAPIController');

            /*
             * Admin Area
             */
            Route::prefix('admin')->group(function () {
                Route::resource('transportation_states', 'Admin\TransportationStatesAPIController');
            });
        });

    /*
     * Comun Area
     */
    Route::resource('license_types', 'LicenseTypesAPIController');

    Route::namespace('Lists')->group(function () {
        Route::get('/nomenclators', 'ListsController');
    });
});