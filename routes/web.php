<?php

namespace App\Http\Controllers\Web;

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

Route::middleware(['guest'])->group(function() {
  Route::get('/login', [AuthController::class, 'loginView'])->name('login.loginView');
  Route::post('/login', [AuthController::class, 'login'])->name('login');
  // Route::get('/register', [AuthController::class, 'registerView'])->name('register.registerView');
  // Route::post('/register', [AuthController::class, 'register'])->name('register');
});


// WITH AUTHENTICATION
Route::middleware(['auth'])->group(function() {
  Route::get('/dashboard', DashboardController::class)->name('dashboard');
  Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

  // COIN
  Route::prefix('/coins')->name('coins.')->group(function() {
    Route::get('/', [CoinTransactionController::class, 'index'])->name('index');
  });


  // ONLY ADMIN
  Route::middleware(['admin'])->group(function() {
    // COIN
    Route::prefix('/coins')->name('coins.')->group(function() {
      Route::get('/send', [CoinTransactionController::class, 'send'])->name('send');
      Route::post('/send', [CoinTransactionController::class, 'sendStore'])->name('send.sendStore');
    });

    // NOTIFICATION
    Route::prefix('/notifications')->name('notifications.')->group(function() {
      Route::get('/', [NotificationController::class, 'index'])->name('index');
      Route::view('/send', 'pages.admin.notifications.send')->name('sendView');
      Route::post('/massive', [NotificationController::class, 'massive'])->name('massive');
    });

    // ORDER
    Route::prefix('/orders')->name('orders.')->group(function() {
      Route::get('/', [ProPlayerOrderController::class, 'index'])->name('index');
    });

    // PRO PLAYER
    Route::prefix('/pro-players')->name('pro-players.')->group(function() {
      Route::get('/', [ProPlayerSkillController::class, 'index'])->name('index');
      Route::get('/approve/{proPlayerSkill:id}', [ProPlayerSkillController::class, 'approve'])->name('approve');
      Route::get('/reject/{proPlayerSkill:id}', [ProPlayerSkillController::class, 'reject'])->name('reject');
      Route::get('/ban/{proPlayerSkill:id}', [ProPlayerSkillController::class, 'ban'])->name('ban');
      Route::get('/unban/{id}', [ProPlayerSkillController::class, 'unban'])->name('unban');
    });

    // PROFILE
    Route::prefix('/profile')->name('profile.')->group(function() {
      Route::get('/', [ProfileController::class, 'index'])->name('index');
      Route::put('/', [ProfileController::class, 'update'])->name('update');
    });

    // REPORT
    Route::prefix('/reports')->name('reports.')->group(function() {
      Route::get('/', [ReportController::class, 'index'])->name('index');
    });
    
    // SETTING
    Route::prefix('/setting')->name('setting.')->group(function() {
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

      // BANNER SETTING
      Route::prefix('/banners')->name('banners.')->group(function() {
        Route::get('/', [AppBannerController::class, 'index'])->name('index');
        Route::post('/', [AppBannerController::class, 'store'])->name('store');
        Route::put('/{bannerId}', [AppBannerController::class, 'update'])->name('update');
        Route::delete('/{bannerId}', [AppBannerController::class, 'destroy'])->name('destroy');
      });

      // COIN SETTING
      Route::prefix('/coins')->name('coins.')->group(function() {
        Route::get('/', [PredefineCoinController::class, 'index'])->name('index');
        Route::post('/', [PredefineCoinController::class, 'store'])->name('store');
        Route::put('/{coinId}', [PredefineCoinController::class, 'update'])->name('update');
        Route::delete('/{coinId}', [PredefineCoinController::class, 'destroy'])->name('destroy');
      });

      // FAQ SETTING
      Route::prefix('/faqs')->name('faqs.')->group(function() {
        Route::get('/', [FAQController::class, 'index'])->name('index');
        Route::post('/', [FAQController::class, 'store'])->name('store');
        Route::put('/{faqId}', [FAQController::class, 'update'])->name('update');
        Route::delete('/{faqId}', [FAQController::class, 'destroy'])->name('destroy');
      });

      // GENDER SETTING
      Route::prefix('/genders')->name('genders.')->group(function() {
        Route::get('/', [GenderController::class, 'index'])->name('index');
        Route::post('/', [GenderController::class, 'store'])->name('store');
        Route::put('/{genderId}', [GenderController::class, 'update'])->name('update');
        Route::delete('/{genderId}', [GenderController::class, 'destroy'])->name('destroy');
      });

      // GENERAL SETTING
      Route::prefix('/general')->name('general.')->group(function() {
        Route::get('/', [AppSettingController::class, 'index'])->name('index');
        Route::put('/', [AppSettingController::class, 'update'])->name('update');
      });

      // SERVICE SETTING
      Route::prefix('/services')->name('services.')->group(function() {
        Route::get('/', [ServiceController::class, 'index'])->name('index');
        Route::put('/{serviceId}', [ServiceController::class, 'update'])->name('update');
      });

      // TIER SETTING
      Route::prefix('/tiers')->name('tiers.')->group(function() {
        Route::get('/', [TierController::class, 'index'])->name('index');
        Route::post('/', [TierController::class, 'store'])->name('store');
        Route::get('/{tierId}', [TierController::class, 'show'])->name('show');
        Route::put('/{tierId}', [TierController::class, 'update'])->name('update');
        Route::delete('/{tierId}', [TierController::class, 'destroy'])->name('destroy');
      });

      // AVAILABLE TRANSFER SETTING
      Route::prefix('/available-transfers')->group(function() {
        Route::get('/', []);
      });
    });

    // USER
    Route::prefix('/users')->name('users.')->group(function() {
      Route::get('/', [UserController::class, 'index'])->name('index');
      Route::post('/admin', [UserController::class, 'storeAdmin'])->name('storeAdmin');
      Route::delete('/{userId}', [UserController::class, 'destroy'])->name('destroy');
    });

    // WITHDRAW
    Route::prefix('withdraws')->name('withdraws.')->group(function() {
      Route::get('/', [BalanceTransactionController::class, 'indexWithdraw'])->name('index');
      Route::get('/approve/{balanceTransaction:id}', [BalanceTransactionController::class, 'approve'])->name('approve');
      Route::get('/reject/{balanceTransaction:id}', [BalanceTransactionController::class, 'reject'])->name('reject');
    });
  });
  

  // ONLY PLAYER
  Route::middleware(['player'])->group(function() {
    
  });
});