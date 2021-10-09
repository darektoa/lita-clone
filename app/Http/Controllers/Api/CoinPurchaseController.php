<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{Coin, CoinPurchase, Player};
use Exception;
use Illuminate\Http\Request;

class CoinPurchaseController extends Controller
{
    public function store(Request $request) {
        $user = auth()->user();
        $coin = Coin::find($request->coin_id);

        try{
            if(!$coin) throw new Exception('Invalid Coin');
            if($user->admin) $this->storeByAdmin($request);
            if($user->player) $this->storeByPlayer($request);
            return response()->json(['message' => 'Successfully request']);
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            return response()->json(['message' => $errMessage]);
        }
    }


    private function storeByAdmin(Request $request) {
        $player = Player::find($request->player_id);

        if(!$player) throw new Exception('User not found');
        
        $coinPurchase = CoinPurchase::create([
            'player_id' => $request->player_id,
            'admin_id' => auth()->user()->admin->id,
            'coin_id' => $request->coin_id,
            'status' => 2,
        ]);

        $player->coin += $coinPurchase->coin->coin;
        $player->save();
    }


    private function storeByPlayer(Request $request) {
        CoinPurchase::create([
            'player_id' => auth()->user()->player->id,
            'coin_id' => $request->coin_id
        ]);
    }
}
