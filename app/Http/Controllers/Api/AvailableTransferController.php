<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Models\AvailableTransfer;
use App\Http\Controllers\Controller;
use App\Http\Resources\AvailableTransferResource;
use Illuminate\Http\Request;

class AvailableTransferController extends Controller
{
    public function index() {
        $availables = AvailableTransfer::all();

        return ResponseHelper::make(
            AvailableTransferResource::collection($availables)
        );
    }
}
