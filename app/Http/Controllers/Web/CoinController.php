<?php

namespace App\Http\Controllers\Web;

use App\Models\CoinPurchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    public function index(Request $request) {
        $user = auth()->user();
        $purchases = new CoinPurchase;
        $statusId = (int)$request->status;

        if($statusId >= 0 && $statusId < 5) $purchases = $purchases->where('status', $statusId);
        if($user->admin) $purchases = $purchases->latest();
        else $purchases = $purchases->where('player_id', $user->player->id);

        $purchases = $purchases
            ->paginate(10)
            ->withQueryString();

        return view('pages.general.coin.index', compact('purchases'));
    }


    public function store() {
        CoinPurchase::create([
            'player_id' => auth()->user()->id,
            'coin_id' => 1,
        ]);
    }
}
