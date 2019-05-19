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
    Route::post('auth/login', 'AuthController@login');
    Route::post('auth/register', 'AuthController@register');
});

Route::group(['prefix' => 'admin'], function () {
    Route::resource('transportation_states', 'Admin\TransportationStatesAPIController');
});
