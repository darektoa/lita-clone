<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Models\{Coin, CoinPurchase};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    public function index() {
        $coins = Coin::all();

        return ResponseHelper::make($coins);
    }
}
