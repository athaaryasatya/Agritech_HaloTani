<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PetaniController;

Route::get('/petani', [PetaniController::class, 'index']);
Route::get('/petani/{id}', [PetaniController::class, 'show']);
Route::post('/petani', [PetaniController::class, 'store']);
Route::put('/petani/{id}', [PetaniController::class, 'update']);