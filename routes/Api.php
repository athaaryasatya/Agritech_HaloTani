<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KeluhanApiController;

Route::get('/keluhan', [KeluhanApiController::class, 'index']);
Route::post('/keluhan', [KeluhanApiController::class, 'store']);
Route::put('/keluhan/{id}', [KeluhanApiController::class, 'update']);
Route::delete('/keluhan/{id}', [KeluhanApiController::class, 'destroy']);