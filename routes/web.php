<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/upload', function () {
    return view('files.upload');
})->name('files.upload');

Route::post('/upload', [FileController::class, 'upload'])->name('files.store');


Route::get('/simple-upload', function () {
    return view('files.simpleUpload');
})->name('files.simple-upload');
Route::post('/simple-upload', [FileController::class, 'simpleUpload'])->name('files.simple-store');
