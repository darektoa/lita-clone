<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProPlayerSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProPlayerSkillController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'game_id'       => 'bail|required|exists:games,id',
            'game_user_id'  => 'required|alpha_num|min:2|max:20',
            'game_tier'     => 'required|min:2|max:50',
            'game_roles'    => 'required|min:2|max:255',
            'game_level'    => 'required|digits_between:1,6'
        ]);

        $errors = $validator->errors();
        if($validator->fails()) {
            return response()->json([
                'message'   => 'Invalid field',
                'errors'    => $errors->all()
            ], 422);
        }

        if(!isset(auth()->user()->player))
            return response()->json(['message' => 'Only player can become a pro player']);
        
        ProPlayerSkill::create([
            'player_id'     => auth()->user()->player->id,
            'game_id'       => $request->game_id,
            'game_user_id'  => $request->game_user_id,
            'game_tier'     => $request->game_tier,
            'game_roles'    => $request->game_roles,
            'game_level'    => $request->game_level
        ]);

        return response()->json(['message' => 'Request sent successfully']);
    }
}
