<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppBannerResource;
use App\Models\AppBanner;
use Illuminate\Http\Request;

class AppBannerController extends Controller
{
    public function index() {
        $banners    = AppBanner::all();

        return ResponseHelper::make(
            AppBannerResource::collection($banners)
        );
    }
}
