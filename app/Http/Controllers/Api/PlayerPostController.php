<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{PlayerPost, PlayerPostMedia, User};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerPostController extends Controller
{
    public function store(Request $request) {
        try{
            $validator  = Validator::make($request->all(), [
                'text'  => 'nullable|max:4000'
            ]);

            if($validator->fails())
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    => $validator->errors()->all(),
                ]);

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
