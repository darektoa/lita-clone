<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use Illuminate\Http\Request;

class CoinTransactionController extends Controller
{
    public function index(Request $request) {
        $typeId         = $request->type;
        $transactions   = new CoinTransaction;

        if($typeId !== null & $typeId >= 0 && $typeId <= 2)
            $transactions = $transactions->where('type', $typeId);

        $transactions = $transactions
            ->latest()
            ->paginate(10);

        return view('pages.general.coin.index', compact('transactions'));
    }
}
