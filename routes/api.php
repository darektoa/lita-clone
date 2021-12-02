<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/sso', [AuthController::class, 'loginSSO']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);

// BANNER
Route::prefix('/banners')->group(function() {
    Route::get('/', [AppBannerController::class, 'index']);
});

// COIN
Route::prefix('/coins')->group(function() {
    Route::get('/', [PredefineCoinController::class, 'index']);
});

// FAQ
Route::prefix('/faqs')->group(function() {
    Route::get('/', [FAQController::class, 'index']);
});

// GAME
Route::prefix('/games')->group(function() {
    Route::get('/', [GameController::class, 'index']);
    Route::get('/tiers', [GameTierController::class, 'index']);
    Route::get('/roles', [GameRoleController::class, 'index']);
    Route::get('/tiers/{game:id}', [GameTierController::class, 'show']);
    Route::get('/roles/{game:id}', [GameRoleController::class, 'show']);
});

// GENDER
Route::prefix('/genders')->group(function() {
    Route::get('/', [GenderController::class, 'index']);
});


// WITH AUTHENTICATION
Route::middleware(['auth.api'])->group(function() {
    Route::prefix('/coins')->group(function() {
        Route::prefix('histories')->group(function() {
            Route::get('/', [CoinTransactionController::class, 'index']);
            Route::get('/{transactionId}', [CoinTransactionController::class, 'show']);
        });

        Route::post('/topup', [CoinTransactionController::class, 'store']);
    });

    // NOTIFICATION
    Route::prefix('/notifications')->group(function() {
        Route::get('/unsubscribe', [NotificationController::class, 'unsubscribe']);
    });

    // PRO PLAYER
    Route::prefix('/pro-players')->group(function() {
        Route::prefix('/skill')->group(function() {
            Route::get('/applied', [ProPlayerSkillController::class, 'applied']);
            Route::post('/register', [ProPlayerSkillController::class, 'store']);
            Route::post('/{proPlayerSkill:id}/order', [ProPlayerSkillController::class, 'order']);
            Route::post('/{proPlayerSkill:id}/unorder', [ProPlayerSkillController::class, 'unorder']);
            Route::get('/{proPlayerSkill:id}/end-order', [ProPlayerSkillController::class, 'endOrder']);
        });

        Route::get('/{player:id}/follow', [ProPlayerController::class, 'follow']);
        Route::get('/{player:id}/unfollow', [ProPlayerController::class, 'unfollow']);
    });

    // ORDER
    Route::prefix('/orders')->group(function() {
        Route::get('/', [ProPlayerOrderController::class, 'index']);
        Route::post('/{proPlayerOrder:id}/review', [ProPlayerOrderController::class, 'review']);
    });

    // PROFILE
    Route::prefix('/profile')->group(function() {
        Route::get('/', [ProfileController::class, 'index']);
        Route::put('/', [ProfileController::class, 'update']);
    });


    // ONLY PRO PLAYER
    Route::middleware(['pro.player.api'])->group(function() {
        Route::prefix('/balances')->group(function() {
            Route::post('/withdraw', [BalanceTransactionController::class, 'withdraw']);
        });

        Route::prefix('/pro')->group(function() {
            Route::get('/orders', [ProPlayerOrderController::class, 'proIndex']);
            Route::get('/orders/{proPlayerOrder:id}/approve', [ProPlayerOrderController::class, 'approve']);
            Route::get('/orders/{proPlayerOrder:id}/reject', [ProPlayerOrderController::class, 'reject']);
        });

        Route::prefix('/settings')->group(function() {
            Route::put('/activity', [ProPlayerSettingController::class, 'activity']);
        });
    }); 


    // ONLY ADMIN
    Route::middleware(['admin.api'])->group(function() {
        Route::prefix('/user')->group(function() {
            Route::get('/', [UserController::class, 'index']);
        });

        Route::prefix('/topup')->group(function() {
           Route::get('/approve/{coinPurchase:id}', [CoinPurchaseController::class, 'approve']);
           Route::get('/reject/{coinPurchase:id}', [CoinPurchaseController::class, 'reject']); 
        });
    });
});


Route::middleware(['optional.auth.api'])->group(function() {
    // NOTIFICATION
    Route::prefix('/notifications')->group(function() {
        Route::post('/subscribe', [NotificationController::class, 'store']);
    });
    
    // PRO PLAYER
    Route::prefix('/pro-players')->group(function() {
        Route::prefix('/skill')->group(function() {
            Route::get('/', [ProPlayerSkillController::class, 'index']);
            Route::get('/{proPlayerSkill:id}', [ProPlayerSkillController::class, 'show']);
            Route::get('/{proPlayerSkill:id}/reviews', [ProPlayerSkillController::class, 'reviews']);
        });
    
        Route::get('/', [ProPlayerController::class, 'index']);
        Route::get('/search', [ProPlayerController::class, 'search']);
        Route::get('/{player:id}', [ProPlayerController::class, 'show']);
    });
});


// XENDIT
Route::prefix('/xendit')->group(function() {
    Route::middleware(['xendit.callback'])->group(function() {
        Route::post('/callback', [CoinTransactionController::class, 'xenditCallback']);
    });
});