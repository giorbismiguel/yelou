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
        ->name('auth.')
        ->group(function () {
            Route::post('/login', 'AuthController@login')
                ->name('login');

            Route::post('/register', 'AuthController@register')
                ->name('register');

            Route::post('/active', 'AuthController@active')
                ->name('active');

            Route::post('/new_activation_code', 'AuthController@newActivationCode')
                ->name('new_activation_code');
        });

    Route::namespace('Auth')
        ->prefix('password')
        ->name('password.')
        ->group(function () {
            Route::post('/create', 'PasswordResetController@create')
                ->name('create');

            Route::get('/find/{token}', 'PasswordResetController@find')
                ->name('find_token');

            Route::post('/reset', 'PasswordResetController@reset')
                ->name('reset');
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
            Route::prefix('admin')
                ->group(function () {
                Route::resource('transportation_states', 'Admin\TransportationStatesAPIController');
            });
        });

    /*
     * Comun Area
     */
    Route::resource('license_types', 'LicenseTypesAPIController');

    Route::namespace('Lists')
        ->name('lists.')
        ->prefix('lists')
        ->group(function () {
            Route::get('/nomenclators', 'ListsController')
                ->name('get');
        });
});