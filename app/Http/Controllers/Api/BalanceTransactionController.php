<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Models\{AppSetting, BalanceTransaction, User};
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

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable, Invalid field', 422, $errors);
            }

            $amount      = $request->amount;
            $description = $request->description;
            $user        = User::with(['player'])->find(auth()->user()->id);
            $player      = $user->player;

            if($amount > $player->balance) throw new ErrorException(
                'Unprocessable, Amount must be last than or equal to balance', 422,
                ['Amount must be last than or equal to balance']
            );

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

            return ResponseHelper::make($transaction);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
