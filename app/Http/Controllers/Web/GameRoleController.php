<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\{Game, GameRole};
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class GameRoleController extends Controller
{
    public function store(Request $request, $gameId) {
        $request->validate([
            'name' => 'required|min:2|max:50'
        ]);

        try{
            GameRole::create([
                'game_id' => $gameId,
                'name' => $request->name,
            ]);
            Alert::success('Success', 'Game role created successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }


    public function destroy($gameId, $gameRoleId) {
        $game = Game::find($gameId);
        $gameRole = GameRole::find($gameRoleId);

        try{
            if($gameRole && !$game->gameRoles()->find($gameRoleId))
                throw new Exception('Not allowed to delete from this game page', 403);
            if(!$gameRole)
                throw new Exception('Game role not found', 404);
            $gameRole->delete();
            Alert::success('Success', 'Game role deleted successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
