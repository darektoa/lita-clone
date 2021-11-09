<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppBannerResource;
use App\Models\AppBanner;
use Illuminate\Http\Request;

class AppBannerController extends Controller
{
    public function index() {
        $banners    = AppBanner::all();

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => AppBannerResource::collection($banners)
        ]);
    }
}
