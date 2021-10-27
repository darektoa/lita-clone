<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

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

    Route::prefix('/pro-players')->name('pro-players.')->group(function() {
      Route::get('/', [ProPlayerSkillController::class, 'index'])->name('index');
      Route::get('/approve/{proPlayerSkill:id}', [ProPlayerSkillController::class, 'approve'])->name('approve');
      Route::get('/reject/{proPlayerSkill:id}', [ProPlayerSkillController::class, 'reject'])->name('reject');
    });
    
    route::prefix('/setting')->name('setting.')->group(function() {
      Route::prefix('/games')->name('games.')->group(function() {
        Route::get('/', [GameController::class, 'index'])->name('index');
        Route::post('/', [GameController::class, 'store'])->name('store');
        Route::get('/{gameId}', [GameController::class, 'show'])->name('show');
        Route::put('/{gameId}', [GameController::class, 'update'])->name('update');
        Route::delete('/{gameId}', [GameController::class, 'destroy'])->name('destroy');
        Route::post('/{gameId}/tiers', [GameTierController::class, 'store'])->name('tiers.store');
        Route::post('/{gameId}/roles', [GameRoleController::class, 'store'])->name('roles.store');
        Route::delete('/{gameId}/tiers/{gameTierId}', [GameTierController::class, 'destroy'])->name('tiers.destroy');
        Route::delete('/{gameId}/roles/{gameRoleId}', [GameRoleController::class, 'destroy'])->name('roles.destroy');
      });

      Route::prefix('/coins')->name('coins.')->group(function() {
        Route::get('/', [PredefineCoinController::class, 'index'])->name('index');
        Route::post('/', [PredefineCoinController::class, 'store'])->name('store');
        Route::put('/{coinId}', [PredefineCoinController::class, 'update'])->name('update');
        Route::delete('/{coinId}', [PredefineCoinController::class, 'destroy'])->name('destroy');
      });

      Route::prefix('/general')->name('general.')->group(function() {
        Route::get('/', [AppSettingController::class, 'index'])->name('index');
        Route::put('/', [AppSettingController::class, 'update'])->name('update');
      });
    });
  });
  
  Route::middleware(['player'])->group(function() {
    Route::delete('/topup/{id}', [CoinPurchaseController::class, 'destroy'])->name('topup.destroy');
  });
});