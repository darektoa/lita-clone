<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{
    BalanceTransaction,CoinTransaction, Player, ProPlayerOrder, User};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        if($user->admin) return $this->admin($request);
        // if($user->player) return $this->player($request);
    }


    public function admin(Request $request) {
        $balanceTransaction = new BalanceTransaction();
        $coinTransaction    = new CoinTransaction();
        $proPlayerOrder     = new ProPlayerOrder();
        $user               = new User();

        $total = json_decode(collect([
            'balanceTransaction' => [
                'all'       => $balanceTransaction->count(),
                'withdraw'  => $balanceTransaction->where('type', 4)->count(),
            ],
            'coinTransaction' => [
                'all'       => $coinTransaction->count(),
                'paid'      => $coinTransaction->where('status', 'paid')->count(),
                'topup'     => $coinTransaction->where('type', 0)->count(),
            ],
            'proPlayerOrder' => [
                'all'       => $proPlayerOrder->count(),
            ],
            'user' => [
                'all'       => $user->count(),
                'player'    => $user->has('player')->count(),
            ]
        ]));
        
        return view('pages.general.dashboard', compact('total'));
    }
}
