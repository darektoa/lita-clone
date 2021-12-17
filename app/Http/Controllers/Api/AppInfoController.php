<?php

namespace App\Http\Controllers\Api;

use App\Models\AppSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppInfoController extends Controller
{
    public function coinConversion() {
        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => AppSetting::first()->coin_conversion,
        ]);
    }


    public function terms() {
        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => AppSetting::first()->terms_rules,
        ]);
    }
}
