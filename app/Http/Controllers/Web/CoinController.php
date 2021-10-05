<?php

namespace App\Http\Controllers\Web;

use App\Models\CoinPurchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    public function index() {
        $user = auth()->user();
        $purchases = [];
  
        if($user->admin) $purchases = CoinPurchase::latest();
        else $purchases = CoinPurchase::where('player_id', $user->player->id);

        $purchases = $purchases->paginate(10)->withQueryString();        

        return view('pages.general.coin.index', compact('purchases'));
    }


    public function store() {
        CoinPurchase::create([
            'player_id' => auth()->user()->id,
            'coin_id' => 1,
        ]);
    }
}
