<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index() {
        $games = Game::orderBy('name', 'asc')->get();

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => GameResource::collection($games)
        ]);
    }
}
