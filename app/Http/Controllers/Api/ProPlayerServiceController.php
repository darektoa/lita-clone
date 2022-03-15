<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProPlayerServiceResource;
use App\Models\ProPlayerService;
use Illuminate\Http\Request;

class ProPlayerServiceController extends Controller
{
    public function index() {
        $services = ProPlayerService::with(['player.user'])
            ->paginate(10);

        return ResponseHelper::make(
            ProPlayerServiceResource::collection($services)
        );
    }
}
