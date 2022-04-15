<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{AppSetting, CoinTransaction, Player, PredefineCoin, User};
use App\Notifications\PushNotification;
use App\Traits\XenditTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Notification, Validator};
use Illuminate\Support\Str;

class CoinTransactionController extends Controller
{
    use XenditTrait;

    public function index(Request $request) {
        $user               = auth()->user();
        $status             = $request->status;
        $types              = explode(',', $request->type);
        $transactions       = CoinTransaction::where(function($query) use($user) {
            $query->where('receiver_id', $user->id)
                ->orWhere('sender_id', $user->id);
        });
        
        if($status) $transactions     = $transactions->where('status', $status);
        if($types >= 0) $transactions = $transactions->whereIn('type', $types);
            
        $transactions = $transactions
            ->latest()
            ->paginate(10)
            ->toArray();
        
        return response()->json(array_merge([
            'status'    => 200,
            'message'   => 'OK'
        ], $transactions));
    }


    public function show($transactionId) {
        try{
            $user           = auth()->user();
            $transaction    = CoinTransaction::where('id', $transactionId)
                ->where(function($query) use($user) {
                  $query
                    ->where('receiver_id', $user->id)
                    ->orWhere('sender_id', $user->id);
                })
                ->first();

            if(!$transaction) throw new Exception('Not found', 404);

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $transaction
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();
            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ], $errCode);
        }
    }


    public function store(Request $request) {
        try{
            $loggedIn  = auth()->id();
            $validator = Validator::make($request->all(), [
                'player_id'     => $loggedIn ? 'nullable' : 'required|exists:players,id',
                'coin'          => 'required|numeric|digits_between:0,18',
                'description'   => 'nullable|max:255',
            ]);
            
            if($validator->fails()) {
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    => $validator->errors()->all()
                ], 422);
            }
            
            if($request->player_id) {
                $user = User::whereRelation('player', 'id', $request->player_id)->first();
                auth()->login($user);
            }

            $player = auth()->user()->player;
            $referralCode  = $request->referral_code;
            $predefineCoin = PredefineCoin::where('coin', $request->coin)->first();
            $coinToBalance = AppSetting::first()->coin_conversion * $request->coin;
            
            $referralValidator = Validator::make($request->all(), [
                'referral_code' => 'required|exists:players|not_in:'.$player->referral_code,
            ]);

            $transaction = CoinTransaction::create([
                'receiver_id'   => auth()->user()->id,
                'coin'          => $request->coin,
                'balance'       => $predefineCoin->balance ?? $coinToBalance,
                'type'          => 0,
                'description'   => $request->description,
                'referral_code' => $referralValidator->fails() ? null : $referralCode,
            ]);
            
            $transaction = CoinTransaction::with(['receiver'])->find($transaction->id);
            $invoice     = XenditTrait::invoice($transaction);
            $status      = Str::lower($invoice['status']);
            
            $transaction->update([
                'invoice' => [$status => $invoice],
                'status'  => $status
            ]);

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $transaction
            ]);
        }catch(Exception $err){
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();
            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ], $errCode);
        }
    }


    public function xenditCallback(Request $request) {
        try{
            $transaction = CoinTransaction::where('uuid', $request->external_id)->first();
            
            if(!$transaction) throw new Exception('Not found', 404);

            $invoice    = collect($transaction->invoice);
            $status     = Str::lower($request->status);
            $player     = $transaction->receiver->player;
            $referral   = $transaction->referral_code;
            
            $invoice->put($status, $request->all());
            $transaction->update([
                'status'    => $status,
                'invoice'   => $invoice
            ]);

            if($status === 'paid') $player->update([
                'coin'  => $player->coin + $transaction->coin
            ]);

            // SEND COIN TO REFERRER
            if($referral) {
                $coin     = $transaction->coin * (20/100);
                $balance  = AppSetting::first()->coin_conversion * $coin;
                $referrer = Player::with(['user'])->where('referral_code', $referral)->first();
                $referrer->user->coinReceivingTransactions()->create([
                    'coin'      => $coin,
                    'balance'   => $balance,
                    'type'      => 5,
                    'status'    => 'success',
                ]);

                $referrer->update([
                    'coin'  => $referrer->coin + $coin,
                ]);
            }

            // SEND PUSH NOTIFICATION
            if($status === 'paid') $payloads = [
                'title' => "Pembayaran Berhasil",
                'body'  => "Pembayaran {$request->external_id} berhasil di selesaikan",
            ];

            if($status === 'expired') $payloads = [
                'title' => "Pembayaran Kadaluwarsa",
                'body'  => "Pembayaran {$request->external_id} kadaluwarsa",
            ];

            $payloads['timeToLive'] = 2419200; // 28 days
            Notification::send($player->user, new PushNotification($payloads));

            return response()->json([
                'status'    => 200,
                'message'   => 'OK'
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();
            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage,
            ], $errCode);
        }
    }
}
