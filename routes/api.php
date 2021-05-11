<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('orders', 'OrderController');
    Route::apiResource('drivers', 'DriverController');
    Route::apiResource('grouporders', 'GrouporderController');
    Route::get('grouporders/date/{date}', 'GrouporderController@date');
    Route::get('drivers/date/{date}', 'DriverController@date');
    Route::apiResource('delivering', 'DriverController@delivering');
});
Route::post('/login', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout');
