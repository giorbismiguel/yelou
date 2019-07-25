<?php

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
             * User Area
             */
            Route::get('user/me', 'UserAPIController@me')->name('me');
            Route::post('/update/password', 'UserAPIController@updatePassword')->name('update_password');
            Route::post('users/{user}', 'UserAPIController@update')->name('users.update.post');
            Route::resource('users', 'UserAPIController');

            /*
             * Client Area
             */
            Route::resource('routes', 'RouteAPIController');
            Route::resource('request_services', 'RequestServicesAPIController');

            /*
             * Driver Personalize Area
             */
            Route::get('/drivers/request_services', 'DriverRequestServicesAPIController@index')
                ->name('drivers.request_services');
            Route::post('/drivers/availables', 'TransportationAvailableAPIController@driversAvailable')
                ->name('drivers.available');

            /*
             * Drivers Resource Area
             */
            Route::resource('transportation_availables', 'TransportationAvailableAPIController');
            Route::resource('requested_services', 'RequestedServiceAPIController');
            Route::resource('register_gps', 'RegisterGpsAPIController');
            Route::resource('driver_qualifications', 'DriverQualificationAPIController');

            /*
             * Admin Area
             */
            Route::prefix('admin')
                ->group(function () {
                    Route::resource('transportation_states', 'Admin\TransportationStatesAPIController');
                    Route::resource('payment_methods', 'Admin\PaymentMethodAPIController');
                    Route::resource('requested_service_statuses', 'Admin\RequestedServiceStatusAPIController');
                });

            /*
             * Comun Area
             */
            Route::namespace('Lists')
                ->name('lists.')
                ->prefix('lists')
                ->group(function () {
                    Route::get('/nomenclators/request-services', 'ListsController@requestServices')
                        ->name('request_services');
                });

            Route::prefix('distance')
                ->name('distance.')
                ->group(function () {
                    Route::post('/calculate', 'DistanceController@calculateRate')
                        ->name('calculate_rate');
                });
        });

    /*
     * Comun Area
     */
    Route::resource('license_types', 'LicenseTypesAPIController');

    /*
     * Client and Driver Area
     */
    Route::prefix('requested_services')
        ->name('requested_services.')
        ->group(function () {
            Route::get('/request/{service_id}/{driver_id}', 'ProcessRequestedServicesAPIController@accept')
                ->name('accept');
            Route::post('/request/{requested_service_id}', 'ProcessRequestedServicesAPIController@acceptClient')
                ->name('accept.client');
        });

    Route::namespace('Lists')
        ->name('lists.')
        ->prefix('lists')
        ->group(function () {
            Route::get('/nomenclators', 'ListsController')
                ->name('get');
        });
});
