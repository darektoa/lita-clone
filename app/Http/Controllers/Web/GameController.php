<?php

namespace App\Http\Controllers\web;

use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use App\Models\Game;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class GameController extends Controller
{
    public function index() {
        $games = Game::orderBy('name', 'asc')->paginate(10);
        
        return view('pages.admin.setting.games.index', compact('games'));
    }


    public function store(Request $request) {
        try{
            $request->validate([
                'name' => 'required|min:2|max:100',
                'icon' => 'required|image|max:2048'
            ]);

            $iconPath = StorageHelper::put("images/game-icons", $request->icon);

            Game::create([
                'icon' => $iconPath,
                'name' => $request->name,
            ]);
            
            Alert::success('Success', 'Game created successfully');
        } catch(Exception $err) {
            dd($err);
            $errMessage = $err->getMessage();
            Alert::error('Failde', $errMessage);
        }finally{
            return back();
        }
    }


    public function show($gameId) {
        $game = Game::findOrFail($gameId);

        return view('pages.admin.setting.games.show', compact('game'));
    }


    public function destroy($gameId) {
        $game = Game::find($gameId);

        try{
            if(!$game) throw new Exception('Game not found', 404);
            StorageHelper::delete($game->icon);
            $game->delete();
            Alert::success('Success', 'Game deleted successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
