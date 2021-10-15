<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ProPlayerSkill;
use Illuminate\Http\Request;

class ProPlayerSkillController extends Controller
{
    public function index() {
        $proPlayers = ProPlayerSkill::all();

        return view('pages.admin.pro-players.index', compact('proPlayers'));
    }
}
