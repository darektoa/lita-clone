<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\{ProPlayerOrder, ProPlayerSkill};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProPlayerSkillController extends Controller
{
    public function index(Request $request) {
        $sortBy     = $request->sort;
        $proPlayers = ProPlayerSkill::with(['game', 'player', 'player.user']);

        try {
            if(!$proPlayers->first()->$sortBy && $sortBy) 
                throw new Exception('Field to sort not found', 404);
            if($sortBy)
                $proPlayers = $proPlayers->orderBy($sortBy, 'desc');

            $proPlayers = $proPlayers
                ->where('status', 2)
                ->paginate(10)
                ->toArray();

            return response()->json(array_merge([
                'status'    => 200,
                'message'   => 'OK'
            ], $proPlayers));
        } catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();
            return response()->json([
                'status'  => $errCode, 
                'message' => $errMessage
            ], $errCode);
        }
    }


    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'game_id'       => 'bail|required|exists:games,id',
            'game_user_id'  => 'required|alpha_num|min:2|max:20',
            'game_tier'     => 'required|min:2|max:50',
            'game_roles'    => 'required|min:2|max:255',
            'game_level'    => 'required|digits_between:1,6',
            'screenshots'   => 'required|array|max:5',
            'screenshots.*' => 'image|max:10240'
        ]);

        if($validator->fails()) {
            $errors = $validator->errors();
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


    public function show(ProPlayerSkill $proPlayerSkill) {
        $proPlayerSkill->load(['game', 'player', 'player.user']);
        $status = $proPlayerSkill->status;
        
        if($status !== 2) return response()->json([
            'status'    => 404,
            'message'   => 'Not found'
        ]);

        return response()->json(['data' => $proPlayerSkill]);
    }


    public function applied(Request $request) {
        $playerId   = auth()->user()->player->id;
        $mySkills   = ProPlayerSkill::where('player_id', $playerId);
        $statusId   = $request->status;

        if($statusId > -1 && $statusId <= 2)
            $mySkills = $mySkills->where('status', $statusId);
        
        $mySkills = $mySkills
            ->latest()
            ->get();

        return response()->json(['data' => $mySkills]);
    }


    public function order(ProPlayerSkill $proPlayerSkill) {
        try{
            $user  = auth()->user();

            if($user->player->coin < 100)
                throw new Exception('Not enough coins', 422);

            $order = ProPlayerOrder::create([
                'player_id'             => $user->id,
                'pro_player_skill_id'   => $proPlayerSkill->id,
                'coin'                  => 100,
                'balance'               => 15000,
                'status'                => 0
            ]);

            return response()->json([
                'satus'     => 200,
                'message'   => 'OK',
                'data'      => $order
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
