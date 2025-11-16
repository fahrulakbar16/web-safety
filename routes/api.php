<?php

use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\ProfileController;
use Illuminate\Support\Facades\Route;

// --- Auth ---
Route::controller(AuthController::class)->prefix('/v1/auth')->group(function () {
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->middleware('auth:sanctum');
});

// --- Profile ---
Route::middleware('auth:sanctum')->prefix('/v1')->group(function () {
    Route::controller(ProfileController::class)->prefix('/profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index']);
        Route::get('/{id}', [ProfileController::class, 'show']);
        Route::put('/', 'update');
        Route::post('/', 'update'); // Support POST for file uploads
    });
});
