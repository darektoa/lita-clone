<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function show(User $user) {
        return ResponseHelper::make(
            UserResource::make($user->load('player'))
        );
    }
}
