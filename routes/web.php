<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class);

Route::middleware(['guest'])->group(function() {
  Route::get('/login', [AuthController::class, 'loginView'])->name('login.loginView');
  Route::post('/login', [AuthController::class, 'login'])->name('login');
  Route::get('/register', [AuthController::class, 'registerView'])->name('register.registerView');
  Route::post('/register', [AuthController::class, 'register'])->name('register');
});

Route::middleware(['auth'])->group(function() {
  Route::get('/dashboard', DashboardController::class)->name('dashboard');
  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
  Route::get('/coins', [CoinController::class, 'index'])->name('coins.index');
});