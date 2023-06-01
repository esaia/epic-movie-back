<?php

use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::controller(RegisterController::class)->group(function () {
    Route::post('/register', 'store')->name('register');
    Route::get('/email/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/login', 'login');
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout');
        Route::post('/user', 'getUser');
    });
});



Route::controller(ForgetPasswordController::class)->group(function () {
    Route::middleware('guest')->group(function () {
        Route::post('/forgot-password', 'sendPasswordResetLink')->name('password.email');
        Route::get('/reset-password/{token}', 'getToken')->name('password.reset');
        Route::post('/update-password', 'updatePassword')->name('password.update');
    });
});


Route::get('/data', function () {
    return Response()->json([ 'msg' => "this is protected data"], 201);
})->middleware('auth:sanctum');
