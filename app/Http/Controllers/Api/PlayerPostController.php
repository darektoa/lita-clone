<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{PlayerPost, PlayerPostMedia, User};
use Exception;
use Illuminate\Http\Request;

class PlayerPostController extends Controller
{
    public function store(Request $request) {
        try{
            $userId = auth()->user()->id;
            $user   = User::with(['player'])->find($userId);

            $post = PlayerPost::create([
                'player_id' => $user->player->id,
                'text'  => $request->text
            ]);

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $post
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();

            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage,
            ], $errCode);
        }
    }
}
