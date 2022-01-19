<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\BalanceTransaction;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class BalanceTransactionController extends Controller
{
    public function indexWithdraw() {
        $transactions = BalanceTransaction::where('type', 3)
            ->latest()
            ->paginate(10);

        return view('pages.general.withdraws.index', compact('transactions'));
    }


    public function approve(BalanceTransaction $balanceTransaction) {
        try{
            if($balanceTransaction->status != 'pending')
                throw new Exception('Cannot edit response', 422);

            $balanceTransaction->update([
                'status' => 'approved'
            ]);

            Alert::success('Success', 'Successfully Approved');
            return back();
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
            return back();
        }
    }


    public function reject(BalanceTransaction $balanceTransaction) {
        dd($balanceTransaction->amount);
        try{
            if($balanceTransaction->status != 'pending')
                throw new Exception('Cannot edit response', 422);

            $balanceTransaction->status = 'rejected';
            $balanceTransaction->receiver->player->balance += $balanceTransaction->amount;
            $balanceTransaction->receiver->player->update();
            $balanceTransaction->update();
            Alert::success('Success', 'Successfully Rejected');
            return back();
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
            return back();
        }
    }
}
