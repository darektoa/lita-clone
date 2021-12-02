<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProPlayerSettingController extends Controller
{
    public function activity(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'activity' => 'required|numeric',
                'games'    => 'nullable'
            ]);

            if($validator->fails())
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    => $validator->errors(),
                ], 422);

            $user   = auth()->user();
            $games  = $request->games;
            $skills = $user->player
                ->proPlayerSkills()
                ->where('status', 2);

            if($games)
                $skills = $skills->whereIn('game_id', explode(',', $games));

            $skills->update([
                'activity'    => $request->activity
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
