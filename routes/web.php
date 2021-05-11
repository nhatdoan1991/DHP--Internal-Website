<?php

use App\Http\Controllers\QueueOrderController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListController;
use App\Http\Controllers\ArchiveController;
use function GuzzleHttp\Promise\queue;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\DeliveringControler;
use App\Http\Controllers\Driver\DriverHomeController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetController;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\ProfileController;
use App\Notifications\EmailNotification;

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

Route::group(['middleware' => 'role:operator'], function () {
    //route for List with 2 extra function push & delete 
    Route::get('/push/{id}', [ListController::class, 'push'])->name('push');
    Route::delete('/deleteItem/{id}', [ListController::class, 'deleteItem'])->name('deleteItem');
    Route::resource('list', 'ListController')->names([
        'index' => 'list'
    ]);
    //route for Queue
    Route::post('/addgroup', [QueueController::class, 'addgroup']);
    Route::post('/assigngroup', [QueueController::class, 'assignGroup']);
    Route::post('/pushtodelivery', [QueueController::class, 'pushDeliver']);
    Route::resource('queue', 'QueueController')->names([
        'index' => 'queue'
    ]);
    //route for archives
    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive');
    Route::get('/archive/{id}', [ArchiveController::class, 'timeframe']);
    Route::get('search', 'ArchiveController@search');
    //route for delivering orders
    Route::get('/delivering', [DeliveringControler::class, 'index'])->name('delivering');
    Route::post('/editdeliveringstatus', [DeliveringControler::class, 'updateUnfulfilled']);
    Route::post('/pusharchive/{groupid}', [DeliveringControler::class, 'pusharchive']);

    Route::get('/register', [RegisterController::class, 'index'])->name('register');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/editprofile1', [ProfileController::class, 'edit']);
    Route::post('/resetpassword1', [ProfileController::class, 'resetpassword']);
});


Route::group(['middleware' => 'role:driver'], function () {
    Route::get('/driverhome', [DriverHomeController::class, 'index'])->name('driver.home');
    //Route for Login and Register
    Route::get('/driver/{id}', [DriverHomeController::class, 'driver'])->name('driver');
    //Route for Driver
    //swap orders in delivering group
    Route::post('/swaporder', [DriverHomeController::class, 'swaporder']);
    //report an order
    Route::post('/reportorder', [DriverHomeController::class, 'reportorder']);
    Route::post('/markmessageread', [DriverHomeController::class, 'markmessageread']);
    //complete an order
    Route::post('/completeorder', [DriverHomeController::class, 'completeorder']);
    //pickup order
    Route::get('/pickup/{id}', [DriverHomeController::class, 'pickup'])->name('pickup');
    //send auto message upon completion
    Route::post('/sendautomessage', [DriverHomeController::class, 'completeorder']);

    Route::get('/driverprofile', [ProfileController::class, 'driverProfile'])->name('driverprofile');
    Route::post('/editprofile', [ProfileController::class, 'edit']);
    Route::post('/resetpassword', [ProfileController::class, 'resetpassword']);
});


Route::get('/', function () {
    return view('auth.login');
});


Route::post('/register', [RegisterController::class, 'store']);
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LogoutController::class, 'remove'])->middleware('auth')->name('logout');


Route::group(['middleware' => 'guest'], function () {
    // Passsword Recovery
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');

    Route::get('/login', [LoginController::class, 'index'])->name('login');
});
