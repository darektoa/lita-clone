<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CoinPurchase;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();
        $total = collect([]);
        $coinPurchase = null;

        if($user->admin) $coinPurchase = new CoinPurchase;
        else $coinPurchase = CoinPurchase::where('player_id', $user->player->id);
        
        $total->put('approved', $coinPurchase->where('status', 2)->count());
        $total->put('pending',  $coinPurchase->where('status', 0)->count());
        $total->put('rejected', $coinPurchase->where('status', 1)->count());

        return view('pages.general.dashboard', compact('total'));
    }
}
