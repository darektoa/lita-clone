<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/sso', [AuthController::class, 'loginSSO']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth.api'])->group(function() {
    Route::prefix('/coin')->group(function() {
        Route::get('/', [CoinController::class, 'index']);
        Route::get('/history', [CoinController::class, 'history']);
    });

    Route::prefix('/topup')->group(function() {
        Route::post('/', [CoinPurchaseController::class, 'store']);
    });

    Route::prefix('/games')->group(function() {
        Route::get('/', [GameController::class, 'index']);
        Route::get('/tiers', [GameTierController::class, 'index']);
        Route::get('/roles', [GameRoleController::class, 'index']);
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