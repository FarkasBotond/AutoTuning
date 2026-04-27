<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarBrandController;
use App\Http\Controllers\CarModelController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TuningProductController;
use App\Http\Controllers\TuningCompanyController;
use App\Http\Controllers\ServiceCategoryController;

Route::post('/registration', [RegistrationController::class, 'registration'])->name('registration');

Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show')->whereNumber('id');

Route::post('/login', [AuthController::class, 'authenticate'])->name('login');

// Public read-only routes
Route::get('/car-brands', [CarBrandController::class, 'index'])->name('car-brands.index');
Route::get('/car-brands/{carBrand}', [CarBrandController::class, 'show'])->name('car-brands.show');
Route::get('/car-models', [CarModelController::class, 'index'])->name('car-models.index');
Route::get('/car-models/{carModel}', [CarModelController::class, 'show'])->name('car-models.show');

Route::get('/tuning-products', [TuningProductController::class, 'index'])->name('tuning-products.index');
Route::get('/tuning-products/{tuningProduct}', [TuningProductController::class, 'show'])->name('tuning-products.show')->whereNumber('tuningProduct');

Route::get('/tuning-companies', [TuningCompanyController::class, 'index'])->name('tuning-companies.index');
Route::get('/tuning-companies/{tuningCompany}', [TuningCompanyController::class, 'show'])->name('tuning-companies.show')->whereNumber('tuningCompany');

Route::get('/service-categories', [ServiceCategoryController::class, 'index'])->name('service-categories.index');
// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::put('/profile/email', [UserController::class, 'updateEmail'])->name('profile.email.update');
    Route::put('/profile/password', [UserController::class, 'updatePassword'])->name('profile.password.update');
});

// Admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/car-brands', [CarBrandController::class, 'store'])->name('car-brands.store');
    Route::put('/car-brands/{carBrand}', [CarBrandController::class, 'update'])->name('car-brands.update');
    Route::delete('/car-brands/{carBrand}', [CarBrandController::class, 'destroy'])->name('car-brands.destroy');
    Route::post('/car-models', [CarModelController::class, 'store'])->name('car-models.store');
    Route::put('/car-models/{carModel}', [CarModelController::class, 'update'])->name('car-models.update');
    Route::delete('/car-models/{carModel}', [CarModelController::class, 'destroy'])->name('car-models.destroy');
});