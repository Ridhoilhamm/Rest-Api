<?php

use App\Http\Controllers\api\Auth\AuthController as AuthAuthController;
use App\Http\Controllers\api\Booking\BookingController;
use App\Http\Controllers\api\v1\LapanganController;
use App\Http\Controllers\Role\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/fields', [LapanganController::class, 'index']);
    Route::prefix('v1')->group(function () {
        Route::get('/fields', [LapanganController::class, 'index']);

        Route::get('/fields/{id}', [LapanganController::class, 'show']);
        Route::post('/fields', [LapanganController::class, 'store']);
        Route::delete('/fields/{id}', [LapanganController::class, 'destroy']);
        Route::get('/v1/fields/deleted', [LapanganController::class, 'getDeletedFields']);
    });
});

Route::prefix('v1')->group(function () {});

Route::post('/register', [AuthAuthController::class, 'register']);
Route::post('/login', [AuthAuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/bookings', [BookingController::class, 'store']);
        Route::get('/bookings', [BookingController::class, 'index']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/roles', [RoleController::class, 'index']);
        Route::post('/roles', [RoleController::class, 'store']);
        Route::get('/roles/{id}', [RoleController::class, 'show']);
        Route::put('/roles/{id}', [RoleController::class, 'update']);
        Route::delete('/roles/{id}', [RoleController::class, 'destroy']);
    });
});
