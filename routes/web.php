<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\web\GameController;
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
  Route::get('/topup', [CoinPurchaseController::class, 'index'])->name('topup.index');
  Route::post('/topup', [CoinPurchaseController::class, 'store'])->name('topup.store');

  Route::middleware(['admin'])->group(function() {
    Route::get('/topup/approve/{coinPurchase:id}', [CoinPurchaseController::class, 'approve'])->name('topup.approve');
    Route::get('/topup/reject/{coinPurchase:id}', [CoinPurchaseController::class, 'reject'])->name('topup.reject');
    
    route::prefix('/setting')->name('setting.')->group(function() {
      Route::get('/game', [GameController::class, 'index'])->name('game.index');
      Route::post('/game', [GameController::class, 'store'])->name('game.store');
    });
  });
  
  Route::middleware(['player'])->group(function() {
    Route::delete('/topup/{id}', [CoinPurchaseController::class, 'destroy'])->name('topup.destroy');
  });
});