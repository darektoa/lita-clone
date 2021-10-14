<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\{GameTier};
use Exception;
use Illuminate\Http\Request;

class GameTierController extends Controller
{
    public function store(Request $request, $gameId) {
        $request->validate([
            'name' => 'required|min:2|max:50'
        ]);

        try{
            GameTier::create([
                'game_id' => $gameId,
                'name' => $request->name,
            ]);
            Alert::success('Success', 'Game tier successfully created');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
