<?php

namespace App\Http\Controllers\Api;

use App\Models\ProPlayerSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/sso', [AuthController::class, 'loginSSO']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);

Route::prefix('/games')->group(function() {
    Route::get('/', [GameController::class, 'index']);
    Route::get('/tiers', [GameTierController::class, 'index']);
    Route::get('/roles', [GameRoleController::class, 'index']);
    Route::get('/tiers/{game:id}', [GameTierController::class, 'show']);
    Route::get('/roles/{game:id}', [GameRoleController::class, 'show']);
});

Route::middleware(['auth.api'])->group(function() {
    Route::prefix('/coin')->group(function() {
        Route::get('/', [CoinController::class, 'index']);
        Route::get('/history', [CoinController::class, 'history']);
    });

    Route::prefix('/topup')->group(function() {
        Route::post('/', [CoinPurchaseController::class, 'store']);
    });

    Route::prefix('/pro-players')->group(function() {
        Route::prefix('/skill')->group(function() {
            Route::get('/', [ProPlayerSkillController::class, 'index']);
            Route::get('/applied', [ProPlayerSkillController::class, 'applied']);
            Route::get('/{proPlayerSkill:id}', [ProPlayerSkillController::class, 'show']);
        });

        Route::post('/register', [ProPlayerSkillController::class, 'store']);
        Route::get('/{player:id}', [ProPlayerController::class, 'index']);
    });

    Route::prefix('/profile')->group(function() {
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