<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin', 'HomeController@index');

Route::prefix('admin')->group(function () {
    Auth::routes();

    Route::resource('transportationStates', 'Admin\TransportationStatesController', ["as" => 'admin']);
    Route::resource('paymentMethods', 'Admin\PaymentMethodController', ["as" => 'admin']);
    Route::resource('requestedServiceStatuses', 'Admin\RequestedServiceStatusController', ["as" => 'admin']);
});

Route::get('/{any?}', 'SpaController')->where('any', '.*');
