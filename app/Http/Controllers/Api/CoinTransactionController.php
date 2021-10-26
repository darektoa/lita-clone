<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use App\Models\PredefineCoin;
use App\Traits\XenditTrait;
use Exception;
use Illuminate\Http\Request;
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
            ->paginate(10);
        
        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => $transactions
        ]);
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
            
            $transaction = CoinTransaction::create([
                'receiver_id'   => auth()->user()->id,
                'coin'          => $request->coin,
                'balance'       => $predefineCoin->balance ?? 1,
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

            $invoice     = collect(json_decode($transaction->invoice));
            $status      = Str::lower($request->status);

            $invoice->put($status, $request->all());
            $transaction->update([
                'status'    => $status,
                'invoice'   => $invoice
            ]);

            return response()->json([
                'status'    => 200,
                'message'   => 'OK'
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();
            response()->json([
                'status'    => $errCode,
                'message'   => $errMessage,
            ], $errCode);
        }
    }
}
