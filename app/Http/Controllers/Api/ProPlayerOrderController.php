<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProPlayerOrder;
use Illuminate\Http\Request;

class ProPlayerOrderController extends Controller
{
    public function index(Request $request) {
        $player = auth()->user()->player;
        $orders = ProPlayerOrder::with(['proPlayerSkill'])
            ->where('player_id', $player->id)
            ->latest()
            ->paginate(10);

        return response()->json(
            collect([
                'status'    => 200,
                'message'   => 'OK'
            ])
            ->merge($orders)
        );
    }
}
