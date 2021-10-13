<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index() {
        $games = Game::orderBy('name', 'asc')->paginate(10);
        
        return view('pages.admin.setting.game.index', compact('games'));
    }
}
