<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PredefineCoin;
use Illuminate\Http\Request;

class PredefineCoinController extends Controller
{
    public function index() {
        $predefineCoins = PredefineCoin::all();

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => $predefineCoins
        ]);
    }
}
