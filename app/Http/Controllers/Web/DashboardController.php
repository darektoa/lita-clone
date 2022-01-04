<?php

namespace App\Http\Controllers\Web;

use App\Helpers\DateHelper;
use App\Http\Controllers\Controller;
use App\Models\{BalanceTransaction,CoinTransaction, ProPlayerOrder, User};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = auth()->user();

        if($user->admin) return $this->admin($request);
        if($user->player) return $this->player($request);
    }


    public function admin(Request $request) {
        $balanceTransaction = new BalanceTransaction();
        $coinTransaction    = new CoinTransaction();
        $proPlayerOrder     = new ProPlayerOrder();
        $user               = new User();

        // TOTAL DATA
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
            ],
        ]));

        // CHART DATA
        $startDate  = now()->subDays(30);
        $endDate    = now();
        $chart      = json_decode(collect([
            'userRegistration'  => [
                'labels' => DateHelper::range($startDate, $endDate),
                'data'   => User::selectRaw('SUBSTR(created_at, 1, 10) as date, COUNT(*) as total ')
                    ->groupBy('date')
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get()
                    ->pluck('total')
                    ->pad($startDate->diffInDays($endDate), 0)
                    ->toArray(),
            ],
        ]));
        
        return view('pages.general.dashboard', compact('total', 'chart'));
    }


    public function player(Request $request) {
        $user = User::with([
            'coinReceivingTransactions',
            'player'
        ])->find(auth()->user()->id);

        // TOTAL DATA
        $total  = json_decode(collect([
            'coins' => [
                'all'       => $user->player->coin,
            ],
            'coinReceivingTransaction' => [
                'all'       => $user->coinReceivingTransactions->count(),
                'paid'      => $user->coinReceivingTransactions->where('status', 'paid')->count(),
                'pending'   => $user->coinReceivingTransactions->where('status', 'pending')->count(),
                'expired'   => $user->coinReceivingTransactions->where('status', 'expired')->count(),
            ],
        ]));

        return view('pages.general.dashboard', compact('total'));
    }
}
