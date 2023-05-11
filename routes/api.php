<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\LeaveController;
use App\Http\Controllers\Api\LeaveTypeController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResources([
        'departments' => DepartmentController::class,
        'leave-types' => LeaveTypeController::class,
    ]);

    Route::get('/leaves', [LeaveController::class, 'index']);
    Route::get('/leaves/{leave}', [LeaveController::class, 'show']);
    Route::get('/leave-requests', [LeaveController::class, 'getLeaveRequests']);
    Route::post('/leaves', [LeaveController::class, 'store']);
    Route::put('/leaves/{leave}', [LeaveController::class, 'update']);
    Route::put('/leaves/{leave}/send', [LeaveController::class, 'send']);
    Route::put('/leaves/{leave}/approve', [LeaveController::class, 'approve']);
    Route::put('/leaves/{leave}/reject', [LeaveController::class, 'reject']);
    Route::delete('/leaves/{leave}', [LeaveController::class, 'destroy']);

});

Route::post('/login/test', [AuthController::class, 'login']);
