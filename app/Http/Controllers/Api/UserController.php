<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) {
        $id         = $request->id;
        $playerId   = $request->player_id;
        $users      = User::with(['admin', 'player']);

        if($id)
            $users = $users->whereId($id);
        if($playerId)
            $users->whereRelation('player', 'id', $playerId);
        
        $users = $users->get();

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => UserResource::collection($users)
        ]);
    }
}
