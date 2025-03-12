<?php

use App\Http\Controllers\DeviceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/**
 * For simplicity sake we will use the default Laravel Sanctum api to issue tokens to the users.
 *
 */

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->post('/device/register', [DeviceController::class, 'registerDevice']);

Route::middleware('auth:sanctum')->get('/device/info/{id}', [DeviceController::class, 'getDeviceInfo']);

Route::middleware('auth:sanctum')->put('/leasing/update/{id}', [DeviceController::class, 'updateLeasing']);
