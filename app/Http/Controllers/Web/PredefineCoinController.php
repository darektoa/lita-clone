<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PredefineCoin;
use Illuminate\Http\Request;

class PredefineCoinController extends Controller
{
    public function index() {
        $coins = PredefineCoin::all();

        return view('pages.admin.setting.coins.index', compact('coins'));
    }
}
