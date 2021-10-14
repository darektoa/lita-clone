<?php

namespace App\Http\Controllers\web;

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
        $request->validate([
            'name' => 'required|min:2|max:100',
        ]);

        try{
            Game::create([
                'name' => $request->name,
            ]);
            Alert::success('Success', 'Game Successfully Created');
        } catch(Exception $err) {
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
