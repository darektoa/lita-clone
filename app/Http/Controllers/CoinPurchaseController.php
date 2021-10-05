<?php

namespace App\Http\Controllers;

use Alert;
use App\Models\{Coin, CoinPurchase};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use function PHPUnit\Framework\throwException;

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
            if($user->admin) $this->storeAdmin($request);
            if($user->player) $this->storePlayer($request);
            Alert::success('Success', 'Topup Successfully');
            return back();
        }catch(Exception $err) {
            $errMessages = $err->getMessage();
            Alert::error('Failed', $errMessages);
            return back();
        }
    }


    private function storeAdmin(Request $request) {
        CoinPurchase::create([
            'player_id' => $request->player->id,
            'admin_id' => auth()->user()->admin->id,
            'coin_id' => $request->coin,
            'status' => 2,
        ]);
    }


    private function storePlayer(Request $request) {
        CoinPurchase::create([
            'player_id' => auth()->user()->player->id,
            'coin_id' => $request->coin
        ]);
    }
}
