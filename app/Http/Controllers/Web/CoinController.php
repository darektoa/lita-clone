<?php

namespace App\Http\Controllers\Web;

use App\Models\CoinPurchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    public function index() {
        $purchases = CoinPurchase::latest()->paginate(10)->withQueryString();

        return view('pages.general.coins', compact('purchases'));
    }

    public function store() {
        CoinPurchase::create([
            'player_id' => auth()->user()->id,
            'coin_id' => 1,
        ]);
    }
}
