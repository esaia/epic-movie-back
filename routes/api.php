<?php

use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function () {
    Route::post('/register', 'store')->name('register');
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/login', 'login');
    Route::get('/logout', 'logout');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/updateUser/{id}', 'updateUser');
        Route::get('/user', 'getUser');
    });
});



Route::controller(GoogleController::class)->group(function () {
    Route::get('/auth/redirect', 'redirect')->middleware(['api']);
    Route::get('/auth/callback', 'callback');
});


Route::controller(ForgetPasswordController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('/forgot-password', 'sendPasswordResetLink')->name('password.email');
        Route::post('/update-password', 'updatePassword')->name('password.update');
    });
});
