<?php

namespace App\Http\Controllers\Web;

use Alert;
use App\Http\Controllers\Controller;
use App\Models\{Coin, CoinPurchase, Player};
use Exception;
use Illuminate\Http\Request;

class CoinPurchaseController extends Controller
{
    public function index() {
        $coins = Coin::where('coin', '!=', 1)->get();

        return view('pages.general.coin.topup', compact('coins'));
    }

    
    public function store(Request $request) {
        $user = auth()->user();
        $coin = Coin::find($request->coin);

        try{
            if(!$coin) throw new Exception('Invalid Coin');
            if($user->admin) $this->storeByAdmin($request);
            if($user->player) $this->storeByPlayer($request);
            Alert::success('Success', 'Topup Successfully');
            return back();
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
            return back();
        }
    }


    private function storeByAdmin(Request $request) {
        $player = Player::find($request->player_id);

        if(!$player) throw new Exception('User not found');
        
        $coinPurchase = CoinPurchase::create([
            'player_id' => $request->player_id,
            'admin_id' => auth()->user()->admin->id,
            'coin_id' => $request->coin,
            'status' => 2,
        ]);

        $player->coin += $coinPurchase->coin->coin;
        $player->save();
    }


    private function storeByPlayer(Request $request) {
        CoinPurchase::create([
            'player_id' => auth()->user()->player->id,
            'coin_id' => $request->coin
        ]);
    }


    public function destroy($id) {
        $user = auth()->user();

        try{
            if($user->admin) $this->destroyByAdmin($id);
            if($user->player) $this->destroyByPlayer($id);
            Alert::success('Success', 'Successfully Canceled');
            return back();
        } catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
            return back();
        }
    }


    private function destroyByAdmin($id) {
        $coinPurchase = CoinPurchase::find($id);
        $coinPurchase->status = 3;
        $coinPurchase->update();
        $coinPurchase->delete();
    }

    
    private function destroyByPlayer($id) {
        $playerId = auth()->user()->player->id;
        $coinPurchase = CoinPurchase::where('player_id', $playerId)->find($id);

        if(!$coinPurchase) throw new Exception('Invalid Topup Id');
        $coinPurchase->status = 3;
        $coinPurchase->update();
        $coinPurchase->delete();
    }


    public function approve(CoinPurchase $coinPurchase) {
        try{
            if($coinPurchase->status != 0) throw new Exception('Cannot edit response');
            $coinPurchase->status = 2;
            $coinPurchase->player->coin += $coinPurchase->coin->coin;
            $coinPurchase->player->update();
            $coinPurchase->update();
            Alert::success('Success', 'Successfully Approved');
            return back();
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
            return back();
        }
    }
    
    
    public function reject(CoinPurchase $coinPurchase) {
        try{
            if($coinPurchase->status != 0) throw new Exception('Cannot edit response');
            $coinPurchase->status = 1;
            $coinPurchase->update();
            Alert::success('Success', 'Successfully Approved');
            return back();
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
            return back();
        }
    }
}
