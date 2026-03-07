<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/registration', [RegistrationController::class, 'registration'])->name('registration');

Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show')->whereNumber('id');

Route::post('/login', [AuthController::class, 'authenticate'])->name('login');