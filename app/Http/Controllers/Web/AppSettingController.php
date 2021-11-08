<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\{AppSetting, AppBanner};
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AppSettingController extends Controller
{
    public function index() {
        $settings   = AppSetting::first();
        $banners    = AppBanner::all();

        return view('pages.admin.setting.general.index', compact('settings', 'banners'));
    }


    public function update(Request $request) {
        try{
            AppSetting::first()->update([
                'coin_conversion'   => $request->coin_conversion,
                'company_revenue'   => $request->company_revenue,
                'terms_rules'       => $request->terms_rules
            ]);

            Alert::success('Success', 'General settings updated successfully');
        }catch(Exception $err) {
            $errMessage  = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
