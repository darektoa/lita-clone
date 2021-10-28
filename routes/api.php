<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/sso', [AuthController::class, 'loginSSO']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);

// GAME
Route::prefix('/games')->group(function() {
    Route::get('/', [GameController::class, 'index']);
    Route::get('/tiers', [GameTierController::class, 'index']);
    Route::get('/roles', [GameRoleController::class, 'index']);
    Route::get('/tiers/{game:id}', [GameTierController::class, 'show']);
    Route::get('/roles/{game:id}', [GameRoleController::class, 'show']);
});

// COIN
Route::prefix('/coins')->group(function() {
    Route::get('/', [PredefineCoinController::class, 'index']);
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

    // PRO PLAYER
    Route::prefix('/pro-players')->group(function() {
        Route::prefix('/skill')->group(function() {
            Route::get('/applied', [ProPlayerSkillController::class, 'applied']);
            Route::post('/register', [ProPlayerSkillController::class, 'store']);
            Route::get('/{proPlayerSkill:id}/order', [ProPlayerSkillController::class, 'order']);
        });

        Route::get('/{player:id}/follow', [ProPlayerController::class, 'follow']);
        Route::get('/{player:id}/unfollow', [ProPlayerController::class, 'unfollow']);
    });

    // PROFILE
    Route::prefix('/profile')->group(function() {
        Route::get('/', [ProfileController::class, 'index']);
        Route::put('/', [ProfileController::class, 'update']);
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


// PRO PLAYER
Route::prefix('/pro-players')->group(function() {
    Route::prefix('/skill')->group(function() {
        Route::get('/', [ProPlayerSkillController::class, 'index']);
        Route::get('/{proPlayerSkill:id}', [ProPlayerSkillController::class, 'show']);
    });

    Route::get('/', [ProPlayerController::class, 'index']);
    Route::get('/{player:id}', [ProPlayerController::class, 'show']);
});


// XENDIT
Route::prefix('/xendit')->group(function() {
    Route::middleware(['xendit.callback'])->group(function() {
        Route::post('/callback', [CoinTransactionController::class, 'xenditCallback']);
    });
});