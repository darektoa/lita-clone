<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\{Game, GameTier};
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

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
            Alert::success('Success', 'Game tier created successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }


    public function destroy($gameId, $gameTierId) {
        $game = Game::find($gameId);
        $gameTier = GameTier::find($gameTierId);

        try{
            if($gameTier && !$game->gameTiers()->find($gameTierId))
                throw new Exception('Not allowed to delete from this game page', 403);
            if(!$gameTier)
                throw new Exception('Game tier not found', 404);
            $gameTier->delete();
            Alert::success('Success', 'Game tier deleted successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
