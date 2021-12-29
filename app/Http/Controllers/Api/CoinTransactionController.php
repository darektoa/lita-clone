<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{AppSetting, CoinTransaction, PredefineCoin};
use App\Traits\XenditTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CoinTransactionController extends Controller
{
    use XenditTrait;

    public function index(Request $request) {
        $user               = auth()->user();
        $status             = $request->status;
        $transactions       = CoinTransaction::where('receiver_id', $user->id);

        if($status) $transactions = $transactions->where('status', $status);
            
        $transactions = $transactions
            ->orWhere('sender_id', $user->id)
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
            $validator = Validator::make($request->all(), [
                'coin'          => 'required|numeric|digits_between:0,18',
                'description'   => 'max:255'
            ]);

            if($validator->fails()) {
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    => $validator->errors()->all()
                ], 422);
            }

            $predefineCoin = PredefineCoin::where('coin', $request->coin)->first();
            $coinToBalance = AppSetting::first()->coin_conversion * $request->coin;
            
            $transaction = CoinTransaction::create([
                'receiver_id'   => auth()->user()->id,
                'coin'          => $request->coin,
                'balance'       => $predefineCoin->balance ?? $coinToBalance,
                'type'          => 0,
                'description'   => $request->description
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
            
            $invoice->put($status, $request->all());
            $transaction->update([
                'status'    => $status,
                'invoice'   => $invoice
            ]);

            if($status === 'paid') $player->update([
                'coin'  => $player->coin + $transaction->coin
            ]);

            // SEND PUSH NOTIFICATION
            $recipients = Arr::flatten(
                $player
                ->user
                ->deviceIds()
                ->select('device_id')
                ->get()
                ->makeHidden('status_name')
                ->toArray()
            );

            if($status === 'paid') $payloads = [
                'title' => "Pembayaran Berhasil",
                'body'  => "Pembayaran {$request->external_id} berhasil di selesaikan",
            ];

            if($status === 'expired') $payloads = [
                'title' => "Pembayaran Kadaluwarsa",
                'body'  => "Pembayaran {$request->external_id} kadaluwarsa",
            ];

            fcm()->to($recipients) // Must an array
            ->timeToLive(2419200) // 28 days
            ->data($payloads)
            ->notification($payloads)
            ->send();

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
