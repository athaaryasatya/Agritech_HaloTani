<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\KeluhanApiController;
use App\Http\Controllers\Api\PupukApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Otomatis cover: GET /pupuk, GET /pupuk/{id}, POST /pupuk, PUT /pupuk/{id}, DELETE /pupuk/{id}
Route::name('api.')->group(function () {
    // Autentikasi publik (tanpa token)
    Route::post('/register', [AuthApiController::class, 'register'])->name('register');
    Route::post('/login', [AuthApiController::class, 'login'])->name('login');

    // Autentikasi terproteksi (wajib Bearer Token)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthApiController::class, 'me'])->name('me');
        Route::post('/logout', [AuthApiController::class, 'logout'])->name('logout');
    });

    Route::apiResource('pupuk', PupukApiController::class);
    Route::apiResource('keluhan', KeluhanApiController::class);
});
