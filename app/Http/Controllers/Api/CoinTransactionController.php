<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use App\Models\PredefineCoin;
use App\Traits\XenditTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoinTransactionController extends Controller
{
    use XenditTrait;

    public function index() {
        $user               = auth()->user();
        $coinTransactions   = CoinTransaction::where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->paginate(10);
        
        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => $coinTransactions
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
            $transaction->update([
                'invoice' => ['Pending' => XenditTrait::invoice($transaction)]
            ]);

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $transaction
            ]);
        }catch(Exception $err){
            $errCode    = $err->getCode();
            $errMessage = $err->getMessage();
            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ], $errCode ?? 400);
        }
    }


    // public function xenditCallback(Request $request) {
    //     try{
    //         CoinTransaction::where('uuid', $request->external_id)->firstOrFail();

    //     }catch(Exception $err) {
    //         $errCode    = $err->getCode() ?? 400;
    //         $errMessage = $err->getMessage();
    //         response()->json([
    //             'status'    => $errCode,
    //             'message'   => $errMessage,
    //         ], $errCode);
    //     }
    // }
}
