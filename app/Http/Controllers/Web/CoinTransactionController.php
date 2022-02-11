<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{AppSetting, CoinTransaction, Player, PredefineCoin};
use App\Notifications\PushNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use RealRashid\SweetAlert\Facades\Alert;

class CoinTransactionController extends Controller
{
    public function index(Request $request) {
        $search         = $request->search;
        $typeId         = $request->type;
        $transactions   = new CoinTransaction;

        if($typeId !== null & $typeId >= 0 && $typeId <= 3)
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
        $coins          = PredefineCoin::all();
        $coinConversion = AppSetting::first()->coin_conversion;

        return view('pages.admin.coin.send', compact('coins', 'coinConversion'));
    }


    public function sendStore(Request $request) {
        try{
            $this->validate($request, [
                'player_id'     => 'required|numeric|exists:players,id',
                'coin'          => 'required|numeric|digits_between:0,18',
                'type'          => 'required|numeric|in:0,3',
                'description'   => 'nullable|max:255'
            ]); 

            $player         = Player::with('user')->find($request->player_id);
            $predefineCoin  = PredefineCoin::where('coin', $request->coin)->first();
            $coinToBalance  = AppSetting::first()->coin_conversion * $request->coin;

            CoinTransaction::create([
                'sender_id'     => auth()->id(),
                'receiver_id'   => $player->user->id,
                'coin'          => $request->coin,
                'balance'       => $predefineCoin->balance ?? $coinToBalance,
                'type'          => $request->type,
                'status'        => 'success',
                'description'   => $request->description
            ]);

            $player->update([
                'coin'  => $player->coin + $request->coin,
            ]);

            $payloads = [
                'title' => "$request->coin koin berhasil ditambahkan ke saldo koin kamu",
                'body'  => $request->description,
            ];

            Notification::send($player->user, new PushNotification($payloads));

            Alert::success('Success', 'Send coins successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back()->withInput();
        }
    }
}
