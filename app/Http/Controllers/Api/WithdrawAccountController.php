<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Models\WithdrawAccount;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawAccountController extends Controller
{
    public function index() {
        $user       = auth()->user();
        $accounts   = WithdrawAccount::whereRelation('user', 'id', '=', $user->id)
            ->paginate(10);

        return ResponseHelper::paginate($accounts);
    }
}
