<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProPlayerSkill;
use Exception;
use Illuminate\Http\Request;

class ProPlayerSettingController extends Controller
{
    public function online(Request $request) {
        try{
            $user   = auth()->user();
            $games  = explode(',', $request->games);
            $skills = $user->player
                ->proPlayerSkills()
                ->where('status', 2)
                ->whereIn('game_id', $games);

            $skills->update([
                'online'    => 1 // 1 = Online
            ]);

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $skills->get()
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();

            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ], $errCode);
        }
    }
}
