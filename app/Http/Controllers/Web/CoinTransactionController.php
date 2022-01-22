<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use Illuminate\Http\Request;

class CoinTransactionController extends Controller
{
    public function index(Request $request) {
        $search         = $request->search;
        $typeId         = $request->type;
        $transactions   = new CoinTransaction;

        if($typeId !== null & $typeId >= 0 && $typeId <= 2)
            $transactions = $transactions->where('type', $typeId);
        if($search)
            $transactions  = $transactions
            ->whereHas('receiver', function($query) use($search) {
                $query
                ->where('username', 'LIKE', "%$search%")
                ->orWhere('email', 'LIKE', "%$search%")
                ->orWhere('name', 'LIKE', "%$search%");
            });

        $transactions = $transactions
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.general.coin.index', compact('transactions'));
    }
}
