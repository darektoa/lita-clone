<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth.api'])->group(function() {
    Route::prefix('/coin')->group(function() {
        Route::get('/', [CoinController::class, 'index']);
    });
});