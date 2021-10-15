<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Game, GameTier};
use Illuminate\Http\Request;

class GameTierController extends Controller
{
    public function index() {
        $gameTiers = GameTier::leftJoin('games', 'game_tiers.game_id', '=', 'games.id')
            ->select('game_tiers.*', 'games.name as game_name')
            ->get()
            ->groupBy('game_name');

        return response()->json(['data' => $gameTiers]);
    }


    public function show(Game $game) {
        $gameTiers = $game->gameTiers;

        return response()->json(['data' => $gameTiers]);
    }
}
