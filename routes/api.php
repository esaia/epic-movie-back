<?php

use App\Http\Controllers\CommentsController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\QuoteController;
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

Route::middleware('auth:sanctum')->group(function () {
	Route::controller(MovieController::class)->group(function () {
		Route::get('/movies', 'index');
		Route::get('/movies/{id}', 'show');
		Route::post('/movies', 'store');
		Route::post('/movies/{id}', 'update');
		Route::delete('/movies/{id}', 'destroy');
	});

	Route::controller(QuoteController::class)->group(function () {
		Route::get('/quotes', 'index');
		Route::post('/quotes', 'store');
		Route::post('/quotes/{id}', 'update');
		Route::delete('/quotes/{id}', 'destroy');
	});

	Route::controller(CommentsController::class)->group(function () {
		Route::get('/comments/{quoteID}', 'index');
		Route::post('/comments', 'store');
	});

	Route::controller(NotificationController::class)->group(function () {
		Route::get('/notifications', 'index');
		Route::get('/seen/{notificationId}', 'markAsSeen');
	});

	Route::controller(LikeController::class)->group(function () {
		Route::post('/like', 'store');
		Route::delete('/like/{id}', 'destroy');
	});
});

Route::get('/genres', [GenreController::class, 'index']);
