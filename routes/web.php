<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);

Route::get('/login', [AuthController::class, 'loginView'])->name('login.loginView');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'registerView'])->name('register.registerView');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/dashboard', DashboardController::class);
