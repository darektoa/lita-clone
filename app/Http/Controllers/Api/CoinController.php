<?php

namespace App\Http\Controllers\Api;

use App\Models\CoinPurchase;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CoinController extends Controller
{
    public function index(Request $request) {
        
    }


    public function history(Request $request) {
        $user = auth()->user();
        $purchases = CoinPurchase::withTrashed();
        $statusId = (int)$request->status;

        if($statusId >= 0 && $statusId < 5) $purchases = $purchases->where('status', $statusId);
        if($user->admin) $purchases = $purchases->latest();
        else $purchases = $purchases->where('player_id', $user->player->id);

        $purchases = $purchases
            ->paginate(10);
        
        return response()->json(['data' => $purchases]);
    }
}
