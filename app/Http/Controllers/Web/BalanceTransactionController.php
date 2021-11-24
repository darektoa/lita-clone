<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransaction;
use Illuminate\Http\Request;

class BalanceTransactionController extends Controller
{
    public function index() {
        $transactions = BalanceTransaction::latest()->paginate(10);

        return view('pages.general.withdraws.index', compact('transactions'));
    }
}
