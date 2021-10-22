<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use App\Models\PredefineCoin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CoinTransactionController extends Controller
{
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
            
            $topup = CoinTransaction::create([
                'receiver_id'   => auth()->user()->id,
                'coin'          => $request->coin,
                'balance'       => $predefineCoin->balance ?? 1,
                'type'          => 0,
                'description'   => $request->description
            ]);
            $topup->type_name = $topup->typeName();

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $topup
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
}
