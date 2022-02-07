<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{AppSetting, CoinTransaction, Player, PredefineCoin};
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class CoinTransactionController extends Controller
{
    public function index(Request $request) {
        $search         = $request->search;
        $typeId         = $request->type;
        $transactions   = new CoinTransaction;

        if($typeId !== null & $typeId >= 0 && $typeId <= 2)
            $transactions = $transactions->where('type', $typeId);
        if($search)
            $transactions  = $transactions
            ->whereHas('receiver', function($query) use($search) {
                $query
                ->where('username', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%");
            });

        $transactions = $transactions
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.general.coin.index', compact('transactions'));
    }


    public function send() {
        return view('pages.admin.coin.send');
    }


    public function sendStore(Request $request) {
        try{
            $player         = Player::find($request->player_id);
            $predefineCoin  = PredefineCoin::where('coin', $request->coin)->first();
            $coinToBalance  = AppSetting::first()->coin_conversion * $request->coin;

            CoinTransaction::create([
                'sender_id'     => auth()->id(),
                'receiver_id'   => $player->user->id,
                'coin'          => $request->coin,
                'balance'       => $predefineCoin->balance ?? $coinToBalance,
                'type'          => 0,
                'status'        => 'success',
                'description'   => $request->description
            ]);

            $player->update([
                'coin'  => $player->coin + $request->coin,
            ]);

            Alert::success('Success', 'Send coins successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back()->withInput();
        }
    }
}
