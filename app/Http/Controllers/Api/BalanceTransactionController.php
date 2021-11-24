<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{AppSetting, BalanceTransaction};
use Exception;
use Illuminate\Http\Request;

class BalanceTransactionController extends Controller
{
    public function withdraw(Request $request) {
        try{
            $amount      = $request->amount;
            $description = $request->description;
            $user        = auth()->user();
            $app         = AppSetting::first();

            $transaction = BalanceTransaction::create([
                'receiver_id'   => $user->id,
                'balance'       => $amount,
                'coin'          => round($amount/$app->coin_conversion),
                'description'   => $description,
                'type'          => 4,
                'status'        => 'pending',
            ]);

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
                'message'   => $errMessage,
            ]);
        }
    }
}
