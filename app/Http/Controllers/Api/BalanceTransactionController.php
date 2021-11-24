<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{AppSetting, BalanceTransaction, User};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BalanceTransactionController extends Controller
{
    public function withdraw(Request $request) {
        try{
            $validator  = Validator::make($request->all(), [
                'amount'        => 'required|numeric',
                'description'   => 'nullable|max:255'
            ]);

            if($validator->fails())
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    => $validator->errors(),
                ]);

            $amount      = $request->amount;
            $description = $request->description;
            $user        = User::with(['player'])->find(auth()->user()->id);
            $player      = $user->player;

            if($amount > $player->balance)
                throw new Exception('Unprocessable, Amount must be last than or equal to balance', 422);

            $app         = AppSetting::first();
            $transaction = BalanceTransaction::create([
                'receiver_id'   => $user->id,
                'balance'       => $amount,
                'coin'          => round($amount/$app->coin_conversion),
                'description'   => $description,
                'type'          => 4,
                'status'        => 'pending',
            ]);

            $player->balance -= $amount;
            $player->update();

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
            ], $errCode);
        }
    }
}
