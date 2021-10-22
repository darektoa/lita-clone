<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index() {
        $users = User::all();

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => UserResource::collection($users)
        ]);
    }
}
