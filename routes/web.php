<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\{
  AuthController,
  DashboardController,
  HomeController,
};

Route::get('/', HomeController::class);

Route::get('/login', [AuthController::class, 'loginView'])->name('login.loginView');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'registerView'])->name('register.registerView');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/dashboard', DashboardController::class);
