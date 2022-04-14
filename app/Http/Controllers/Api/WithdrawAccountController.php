<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\ResponseHelper;
use App\Models\{User, WithdrawAccount};
use App\Http\Controllers\Controller;
use App\Http\Resources\WithdrawAccountResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawAccountController extends Controller
{
    public function index() {
        $user       = auth()->user();
        $accounts   = WithdrawAccount::whereRelation('user', 'id', '=', $user->id)
            ->paginate(10);

        return ResponseHelper::paginate(WithdrawAccountResource::collection($accounts),'OK', 200);
    }


    public function store(Request $request) {
        try{
            $user       = auth()->user();
            $accounts   = $user->withdrawAccounts;
            $validator  = Validator::make($request->all(), [
                'name'          => 'required|max:100',
                'number'        => 'required|max:20',
                'transfer_id'   => 'required|exists:available_transfers,id'
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable, Invalid field', 422, $errors);
            }

            if($accounts->count() === 3) throw new ErrorException('Unprocessable', 422, [
                'Account has reached the maximum limit'
            ]);

            $account  = WithdrawAccount::create([
                'user_id'       => $user->id,
                'name'          => $request->name,
                'number'        => $request->number,
                'transfer_id'   => $request->transfer_id,
                'default'       => $accounts->count() ? 0 : 1,
            ]);

            return ResponseHelper::make(WithdrawAccountResource::make($account));
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }


    public function update(Request $request, WithdrawAccount $withdrawAccount) {
        try{
            $validator  = Validator::make($request->all(), [
                'name'          => 'nullable|max:100',
                'number'        => 'nullable|max:20',
                'default'       => 'nullable|in:1',
                'transfer_id'   => 'nullable|exists:available_transfers,id'
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable, Invalid field', 422, $errors);
            }

            $userId = auth()->user()->id;
            $user   = User::with('withdrawAccounts')->find($userId);

            if($withdrawAccount->user->id !== $userId)
                throw new ErrorException('Not allowed, this is not your account', 403);
            if($request->default)
                $user->withdrawAccounts()->update([
                    'default'   => 0,
                ]);

            $withdrawAccount->update([
                'name'          => $request->name ?? $withdrawAccount->name,
                'number'        => $request->number ?? $withdrawAccount->number,
                'default'       => $request->default ?? $withdrawAccount->default,
                'transfer_id'   => $request->transfer_id ?? $withdrawAccount->transfer_id,
            ]);

            return ResponseHelper::make(WithdrawAccountResource::make($withdrawAccount));
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }


    public function destroy(WithdrawAccount $withdrawAccount) {
        try{
            $userId = auth()->user()->id;
            $user   = User::with('withdrawAccounts')->find($userId);

            if($withdrawAccount->user->id !== $userId)
                throw new ErrorException('Not allowed, this is not your account', 403);
                
            $withdrawAccount->delete();

            if($withdrawAccount->default && $user->withdrawAccounts()->count())
                $user->withdrawAccounts()->first()->update([
                    'default'   => 1,
                ]);
                
            return ResponseHelper::make(WithdrawAccountResource::make($withdrawAccount));
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
