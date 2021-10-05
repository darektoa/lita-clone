<?php

namespace App\Http\Controllers;

use App\Models\{Coin, CoinPurchase};
use Illuminate\Http\Request;

class CoinPurchaseController extends Controller
{
    public function index() {
        $coins = Coin::where('coin', '!=', 1)->get();

        return view('pages.general.coin.topup', compact('coins'));
    }

    
    public function store(Request $request) {
        $coin = Coin::find($request->coin);

        if($coin) {
            CoinPurchase::create([
                'user_id' => auth()->user()->id,
                'coin_id' => $coin->id
            ]);
            return back();
        }

        return back();
    }
}
