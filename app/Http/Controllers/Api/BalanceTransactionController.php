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
                'account_id'    => 'nullable|numeric',
                'description'   => 'nullable|max:255'
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable, Invalid field', 422, $errors);
            }

            $amount          = $request->amount;
            $description     = $request->description;
            $accountId       = $request->account_id;
            $user            = User::with(['player', 'withdrawAccounts'])->find(auth()->user()->id);
            $player          = $user->player;
            $withdrawAccount = $user->withdrawAccounts()->where('default', 1)->first();

            if($accountId) {
                $withdrawAccount = $user->withdrawAccounts()->find($accountId);
                if(!$withdrawAccount) throw new ErrorException(
                    'Not Found', 404, 
                    ['Withdraw account not found']
                );
            }

            if(!$withdrawAccount) throw new ErrorException(
                'Unprocessable', 422,
                ['Please add a withdrawal account first']
            );

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
                'type'          => 3,
                'status'        => 'pending',
                'detail'        => collect(['withdraw_account' => $withdrawAccount])
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
