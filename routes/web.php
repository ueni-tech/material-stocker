<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/upload', function () {
    return view('files.upload');
})->name('files.upload');
Route::post('/upload', [FileController::class, 'fileUpload'])->name('files.store');
