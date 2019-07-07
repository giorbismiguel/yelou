<?php

Route::get('/admin', 'HomeController@index');

Route::prefix('admin')->group(function () {
    Auth::routes();

    Route::resource('transportationStates', 'Admin\TransportationStatesController', ["as" => 'admin']);
    Route::resource('paymentMethods', 'Admin\PaymentMethodController', ["as" => 'admin']);
    Route::resource('requestedServiceStatuses', 'Admin\RequestedServiceStatusController', ["as" => 'admin']);
});

Route::get('/{any?}', 'SpaController')->where('any', '.*');
