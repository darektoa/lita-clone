<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\ProPlayerSkill;
use Illuminate\Http\Request;

class ProPlayerSkillController extends Controller
{
    public function index(Request $request) {
        $proPlayers = new ProPlayerSkill;
        $statusId   = $request->status;

        if($statusId >= 0 && $statusId <= 2)
            $proPlayers = $proPlayers->where('status', $statusId);
        
        $proPlayers = $proPlayers
            ->oldest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.pro-players.index', compact('proPlayers'));
    }
}
