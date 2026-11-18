<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LaporanController;


Route::get('/', function () {return redirect('/petani');});
// Route::get('/petani/login', LoginPetani::class)->name('filament.petani.auth.login');

Route::get('/export-excel', [LaporanController::class, 'exportExcel'])->name('export.excel');