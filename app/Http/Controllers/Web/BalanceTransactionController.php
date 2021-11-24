<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransaction;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BalanceTransactionController extends Controller
{
    public function index() {
        $transactions = BalanceTransaction::latest()->paginate(10);

        return view('pages.general.withdraws.index', compact('transactions'));
    }


    public function approve(BalanceTransaction $transaction) {
        try{
            if($transaction->status != 'pending')
                throw new Exception('Cannot edit response', 422);

            $transaction->status = 'approved';
            Alert::success('Success', 'Successfully Approved');
            return back();
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
            return back();
        }
    }
}
