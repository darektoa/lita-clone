<?php

namespace App\Http\Controllers\Web;

use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use App\Models\AppBanner;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class AppBannerController extends Controller
{
    public function index() {
        return 'Test';
    }


    public function store(Request $request) {
        try{
            $request->validate([
                'alt'   => 'required|max:100',
                'image' => 'required|image|max:2048'
            ]);

            $imagePath = StorageHelper::put('images/banners', $request->image);

            AppBanner::create([
                'url'   => $imagePath,
                'alt'   => $request->alt,
                'link'  => $request->link
            ]);

            Alert::success('Success', 'Banner created successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
