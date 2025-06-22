<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\JournalController;


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

Route::post('/register', [AccountController::class, 'store']);
Route::post('/login', [AccountController::class, 'login']);
Route::post('/forgot-password', [AccountController::class, 'sendResetCode']);
Route::post('/verify-code', [AccountController::class, 'verifyResetCode']);
Route::post('/reset-password', [AccountController::class, 'resetPassword']);

Route::get('/journals', [JournalController::class, 'index']);
Route::post('/journals', [JournalController::class, 'store']);
Route::get('/journals/{id}', [JournalController::class, 'show']);
Route::delete('/journals/{id}', [JournalController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/profile', [AccountController::class, 'profile']);
Route::middleware('auth:sanctum')->put('/profile', [AccountController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->delete('/profile', [AccountController::class, 'deleteProfile']);
Route::middleware('auth:sanctum')->post('/profile/upload-image', [AccountController::class, 'uploadImage']);
Route::middleware('auth:sanctum')->post('/profile/featured-photo', [AccountController::class, 'uploadFeaturedPhoto']);

Route::middleware('auth:sanctum')->post('/journals', [JournalController::class, 'store']);
Route::middleware('auth:sanctum')->put('/journals/{id}', [JournalController::class, 'update']);
