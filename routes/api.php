<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\SavedPlaceController;
use App\Http\Controllers\AuthenController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\SearchPlaceController;

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

// Place routes
Route::get('/places', [PlaceController::class, 'index']);
Route::post('/places', [PlaceController::class, 'store']);

// Saved places routes
Route::post('/save', [SavedPlaceController::class, 'store']);
Route::delete('/save/{place_id}', [SavedPlaceController::class, 'destroy']);
Route::get('/saved/{user_id}', [SavedPlaceController::class, 'getUserSaved']);

// User profile routes
// User
Route::get('/user/{id}', [UserProfileController::class, 'show']);
Route::put('/user/{id}', [UserProfileController::class, 'update']);
Route::post('/user/upload-profile', [UserProfileController::class, 'uploadProfile']);
Route::post('/user/upload-cover', [UserProfileController::class, 'uploadCover']);

// Posts
Route::get('/posts/{userId}', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store']);
Route::delete('/posts/{id}', [PostController::class, 'destroy']);

// Photos
Route::get('/photos/{userId}', [PhotoController::class, 'index']);
Route::post('/photos/upload', [PhotoController::class, 'upload']);
Route::delete('/photos/{id}', [PhotoController::class, 'destroy']);

// Search places
Route::get('/places', [SearchPlaceController::class, 'index']);   // Search places
Route::post('/places', [SearchPlaceController::class, 'store']);  // Create place
