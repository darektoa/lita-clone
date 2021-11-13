<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
            'data'      => $player
        ]);
    }


    public function follow(Player $player) {
        $user        = auth()->user();
        $isProPlayer = $player->is_pro_player;

        try{
            if(!isset($user->player)) 
                throw new Exception('Unproccessable, Only player can follow other player', 422);
            if($user->player->id === $player->id)
                throw new Exception('Unproccessable, Cannot follow yourself', 422);
            if(!$isProPlayer)
                throw new Exception('Unproccessable, Player is not pro player', 422);
            if($player->followers->where('follower_id', $user->player->id)->first())
                throw new Exception('Unproccessable, Already to following this player', 422);
                
            PlayerFollower::create([
                'following_id'  => $player->id,
                'follower_id'   => $user->player->id
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
    
    
    public function unfollow(Player $player) {
        $user = auth()->user();

        try{
            if(!isset($user->player)) 
                throw new Exception('Unproccessable, Only player can unfollow other player', 422);
            if($user->player->id === $player->id)
                throw new Exception('Unproccessable, Cannot unfollow yourself', 422);
            if(!$player->followers->where('follower_id', $user->player->id)->first())
                throw new Exception("Unproccessable, You haven't followed this player", 422);
                
            PlayerFollower::where('follower_id', $user->player->id)
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
        );
    }
}
