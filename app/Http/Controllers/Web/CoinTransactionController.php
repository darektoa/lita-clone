<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use Illuminate\Http\Request;

class CoinTransactionController extends Controller
{
    public function index() {
        $transactions = CoinTransaction::latest()
            ->paginate(10);

        return view('pages.general.coin.index', compact('transactions'));
    }
}
