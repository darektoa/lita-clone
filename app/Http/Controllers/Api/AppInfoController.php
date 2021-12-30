<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Models\AppSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AppInfoController extends Controller
{
    public function coinConversion() {
        return ResponseHelper::make(
            AppSetting::first()->coin_conversion,
        );
    }


    public function terms() {
        return ResponseHelper::make(
            AppSetting::first()->terms_rules,
        );
    }
}
