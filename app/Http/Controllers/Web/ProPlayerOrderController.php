<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ProPlayerOrder;
use Illuminate\Http\Request;

class ProPlayerOrderController extends Controller
{
    public function index(Request $request) {
        $search     = $request->search;
        $statusId   = $request->satatus;
        $orders     = new ProPlayerOrder;
        
        $orders = $orders
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.orders.index', compact('orders'));
    }
}
