<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function store() {
        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => []
        ]);
    }
}
