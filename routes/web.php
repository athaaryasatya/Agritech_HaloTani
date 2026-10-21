<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PupukController;

Route::get('/', function () {return redirect('/petani');});
Route::resource('pupuk', PupukController::class);