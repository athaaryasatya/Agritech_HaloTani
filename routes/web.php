<?php

use App\Http\Controllers\PupukController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/petani');
Route::get('/pupuk/export/pdf', [PupukController::class, 'exportPdf'])->name('pupuk.export.pdf');
Route::resource('pupuk', PupukController::class);