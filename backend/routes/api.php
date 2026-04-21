<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarBrandController;
use App\Http\Controllers\CarModelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/registration', [RegistrationController::class, 'registration'])->name('registration');

Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show')->whereNumber('id');

Route::post('/login', [AuthController::class, 'authenticate'])->name('login');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('car-brands', CarBrandController::class);
    Route::apiResource('car-models', CarModelController::class);
});