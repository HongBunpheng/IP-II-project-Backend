<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AuthenController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RestaurantController;

Route::controller(HotelController::class)->prefix('hotels')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{hotel}', 'show');
    Route::put('/{hotel}', 'update');
    Route::delete('/{hotel}', 'destroy');
});

Route::controller(RestaurantController::class)->prefix('restaurants')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{restaurant}', 'show');
    Route::put('/{restaurant}', 'update');
    Route::delete('/{restaurant}', 'destroy');
});

Route::controller(AccountController::class)->prefix('accounts')->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{account}', 'show');
    Route::put('/{account}', 'update');
    Route::delete('/{account}', 'destroy');
});

Route::post('/register', [AuthenController::class, 'register']);
Route::post('/login', [AuthenController::class, 'login']);
Route::post('/forgot-password', [AuthenController::class, 'forgotPassword']);
Route::post('/verify-code', [AuthenController::class, 'verifyCode']); // optional
Route::post('/reset-password', [AuthenController::class, 'resetPassword']);

