<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Player};
use Illuminate\Http\Request;

class ProPlayerController extends Controller
{
    public function index() {
        $proPlayers = Player::with('user')
            ->where('is_pro_player', 1)
            ->withCount(['proPlayerSkills' => function($query) {
                $query->where('status', 2);
            }])
            ->orderBy('pro_player_skills_count', 'desc')
            ->paginate(10);

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => $proPlayers,
        ]);
    }


    public function show(Player $player) {
        $player->load('user');

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => $player
        ]);
    }
}
