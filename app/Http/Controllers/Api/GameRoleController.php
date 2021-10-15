<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Game, GameRole};
use Illuminate\Http\Request;

class GameRoleController extends Controller
{
    public function index() {
        $gameRoles = GameRole::leftJoin('games', 'game_roles.game_id', '=', 'games.id')
            ->select('game_roles.*', 'games.name as game_name')
            ->get()
            ->groupBy('game_name');

        return response()->json(['data' => $gameRoles]);
    }


    public function show(Game $game) {
        $gameRoles = $game->gameRoles;

        return response()->json(['data' => $gameRoles]);
    }
}
