<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceId;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function store(Request $request) {
        $user   = auth()->user();

        $deviceId = DeviceId::create([
            'user_id'   => $user->id ?? null,
            'device_id' => $request->device_id,
            'status'    => 1,
        ]);

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => $deviceId,
        ]);
    }
}
