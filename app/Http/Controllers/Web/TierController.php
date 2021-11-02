<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tier;
use Illuminate\Http\Request;

class TierController extends Controller
{
    public function index() {
        $tiers  = Tier::paginate(10);

        return view('pages.admin.setting.tiers.index', compact('tiers'));
    }
}
