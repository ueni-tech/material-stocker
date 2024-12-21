<?php

use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\HomeController;
use App\Livewire\Counter;

Route::middleware('guest')->group(function () {
    Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
    Route::get('login', function () {
        return view('auth.login');
    })->name('login');
});

Route::middleware(['auth'])->get('/', [HomeController::class, 'index'])->name('home');
Route::middleware(['auth'])->get('/logout', [GoogleController::class, 'logout'])->name('logout');


Route::get('/upload', [FileController::class, 'upload'])->name('files.upload');
Route::post('/upload', [FileController::class, 'fileUpload'])->name('files.store');
