<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PlayerResource;
use App\Models\{Player, PlayerFollower, User};
use Exception;
use Illuminate\Http\Request;

class ProPlayerController extends Controller
{
    public function index() {
        $proPlayers = Player::with('user')
            ->where('is_pro_player', 1)
            ->withCount(['proPlayerSkills' => fn($query) => $query->where('status', 2)])
            ->orderBy('pro_player_skills_count', 'desc')
            ->paginate(10);

        return response()->json(
            collect([
                'status'    => 200,
                'message'   => 'OK'
            ])
            ->merge($proPlayers)
            ->merge(['data' => PlayerResource::collection($proPlayers)])
        );
    }


    public function show(Player $player) {
        $player->load([
            'user.player',
            'proPlayerSkills' => fn($query) => $query->where('status', 2),
            'proPlayerSkills.game',
        ]);

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => PlayerResource::make($player)
        ]);
    }


    public function follow(User $user) {
        $follower   = auth()->user()->player;
        $following  = $user->player;

        try{
            if(!isset($follower)) 
                throw new Exception('Unproccessable, Only player can follow other player', 422);
            if($follower->id === $following->id)
                throw new Exception('Unproccessable, Cannot follow yourself', 422);
            if(!$following->is_pro_player)
                throw new Exception('Unproccessable, Player is not pro player', 422);
            if($following->followers->where('follower_id', $follower->id)->first())
                throw new Exception('Unproccessable, Already to following this player', 422);
                
            PlayerFollower::create([
                'following_id'  => $following->id,
                'follower_id'   => $follower->id
            ]);
    
            return response()->json([
                'status'    =>  200,
                'message'   => 'OK, Successfully followed'
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode();
            $errMessage = $err->getMessage();
            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ], $errCode);
        }
    }
    
    
    public function unfollow(User $user) {
        $follower   = auth()->user()->player;
        $following  = $user->player;

        try{
            if(!isset($follower)) 
                throw new Exception('Unproccessable, Only player can unfollow other player', 422);
            if($follower->id === $following->id)
                throw new Exception('Unproccessable, Cannot unfollow yourself', 422);
            if(!$following->followers->where('follower_id', $follower->id)->first())
                throw new Exception("Unproccessable, You haven't followed this player", 422);
                
            PlayerFollower::where('follower_id', $follower->id)
                ->delete();
    
            return response()->json([
                'status'    =>  200,
                'message'   => 'OK, Successfully unfollowed'
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode();
            $errMessage = $err->getMessage();
            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ], $errCode);
        }
    }


    public function search(Request $request) {
        $search     = $request->search;
        $proPlayers = Player::with(['user'])
            ->withCount('proPlayerSkills')
            ->where('is_pro_player', 1)
            ->whereHas('user', function($query) use($search){
                $query
                ->where('users.username', 'LIKE', "%$search%")
                ->orWhere('users.name', 'LIKE', "%$search%");
            })
            ->paginate(10);
        
        return response()->json(
            collect([
                'status'    => 200,
                'message'   => 'OK',
            ])
            ->merge($proPlayers)
            ->merge(['data' => PlayerResource::collection($proPlayers)])
        );
    }
}
