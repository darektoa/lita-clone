<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CoinPurchaseController extends Controller
{
    public function index() {
        return view('pages.user.coin.topup');
    }
}
