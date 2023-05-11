<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LeaveController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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
Route::namespace('App\Http\Controllers')->group(function() {
    Auth::routes([
        'register' => false,
        'reset'    => false,
        'confirm'  => false,
        'verify'   => false,
    ]);
});


Route::get('/login/google', [LoginController::class, 'redirectToProvider'])->name('google-login-redirect');
Route::get('/login/google/callback', [LoginController::class, 'handleProviderCallback'])->name('google-login-callback');

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/leaves', [LeaveController::class, 'index'])->name('leave.index');

    Route::get('{path}', [LeaveController::class, 'redirectToLeaves'])->where('path', '(.*)');

});

