<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    public function index() {
        $settings = AppSetting::first();

        return view('pages.admin.setting.general.index', compact('settings'));
    }
}
