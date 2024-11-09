<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');


Route::get('/upload', function () {
    return view('files.upload');
})->name('files.upload');
Route::post('/upload', [FileController::class, 'fileUpload'])->name('files.store');
