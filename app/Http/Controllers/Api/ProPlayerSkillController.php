<?php

namespace App\Http\Controllers\Api;

use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\{ProPlayerSkillResource, ProPlayerOrderResource, ProPlayerOrderReviewResource};
use App\Models\{ProPlayerOrder, ProPlayerSkill, ProPlayerSkillScreenshot, User};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ProPlayerSkillController extends Controller
{
    public function index(Request $request) {
        $playerId   = auth()->user()->player->id ?? null;
        $sortBy     = $request->sort;
        $sortValue  = $request->sort_value;
        $search     = $request->search;
        $proPlayers = ProPlayerSkill::with([
            'game',
            'player.user',
            'tier',
            'proPlayerSkillScreenshots'
        ]);

        try {
            if($proPlayers->first() && !$proPlayers->first()->$sortBy && $sortBy) 
                throw new Exception('Field to sort not found', 404);
            if($sortBy)
                $proPlayers = $proPlayers->orderBy($sortBy, 'desc');
            if($sortBy && $sortValue)
                $proPlayers = $proPlayers->where($sortBy, $sortValue);
            if($search)
                $proPlayers = $proPlayers->whereHas('player', function($query) use($search) {
                    $query->whereRelation('user', 'username', 'LIKE', "%$search%");
                });

            $proPlayers = $proPlayers
                ->where('status', 2) // 2 = Approved
                //->where('activity', 1) // 1 = Online
                // ->where('player_id', '!=', $playerId)
                ->inRandomOrder()
                ->paginate(10);

            return response()->json(
                collect([
                    'status'    => 200,
                    'message'   => 'OK'
                ])
                ->merge($proPlayers)
                ->merge(['data' => ProPlayerSkillResource::collection($proPlayers)])
            );
            
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
        try{
            $validator = Validator::make($request->all(), [
                'game_id'       => 'bail|required|exists:games,id',
                'game_user_id'  => 'required|alpha_num|min:2|max:20',
                'game_tier'     => 'required|min:2|max:50',
                'game_roles'    => 'required|min:2|max:255',
                'game_level'    => 'required|digits_between:1,6',
                'screenshots'   => 'required|array|max:5',
                'screenshots.*' => 'image|max:10240'
            ]);
    
            if($validator->fails())
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    => $validator->errors()->all()
                ], 422);
    
            $user          = User::with('player.proPlayerSkills')->find(auth()->user()->id);
            $validatorUser = Validator::make($user->toArray(), [
                '*'                 => 'required',
                'email_verified_at' => 'nullable',
                'remember_token'    => 'nullable',
                'deleted_at'        => 'nullable'
            ]);
            
            if($validatorUser->fails())
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Please complete the user profile',
                    'errors'    => $validatorUser->errors(),
                ], 422);
    
            if(!isset($user->player))
                throw new Exception('Only player can become a pro player', 403);

            $skills = $user->player->proPlayerSkills;
            if($skills->where('game_id', $request->game_id)->where('status', 0)->first())
                throw new Exception('You have submitted this game', 422);
            if($skills->where('game_id', $request->game_id)->where('status', 2)->first())
                throw new Exception('You are already a pro player in this game', 422);
            
            
            $proPlayerSkill = ProPlayerSkill::create([
                'player_id'     => auth()->user()->player->id,
                'game_id'       => $request->game_id,
                'game_user_id'  => $request->game_user_id,
                'game_tier'     => $request->game_tier,
                'game_roles'    => $request->game_roles,
                'game_level'    => $request->game_level
            ]);
            
            // INSERT A PLAYER SKILL SCREENSHOTS
            foreach($request->screenshots as $screenshot) {
                $screenshotPath = StorageHelper::put('images/pro-players/skills', $screenshot);
                ProPlayerSkillScreenshot::create([
                    'pro_player_skill_id'   => $proPlayerSkill->id,
                    'url'                   => $screenshotPath
                ]);
            };
    
            $proPlayerSkill = ProPlayerSkill::with('proPlayerSkillScreenshots')
                ->find($proPlayerSkill->id);
    
            return response()->json([
                'status'  => 200,
                'message' => 'OK',
                'data'    => new ProPlayerSkillResource($proPlayerSkill)
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


    public function show(ProPlayerSkill $proPlayerSkill) {
        try{
            $proPlayerSkill->load([
                'game',
                'player.user',
                'proPlayerSkillScreenshots'
            ]);
            $status = $proPlayerSkill->status;
            
            if($status !== 2) throw new Exception('Not found', 404);
    
            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => ProPlayerSkillResource::make($proPlayerSkill)
            ]);
        }catch(Exception $err) {
            $errCode        = $err->getCode() ?? 400;
            $errMessage     = $err->getMessage();
            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ], $errCode);
        }
    }


    public function applied(Request $request) {
        $playerId   = auth()->user()->player->id;
        $mySkills   = ProPlayerSkill::with(['game']);
        $statusId   = $request->status;

        if($statusId > -1 && $statusId <= 2)
            $mySkills = $mySkills->where('status', $statusId);
        
        $mySkills = $mySkills
            ->where('player_id', $playerId)
            ->latest()
            ->get();

        return response()->json([
            'satatus'   => 200,
            'message'   => 'OK',
            'data'      => ProPlayerSkillResource::collection($mySkills)
        ]);
    }


    public function order(Request $request, ProPlayerSkill $proPlayerSkill) {
        try{
            $validator = Validator::make($request->all(), [
                'expiry_duration'   => 'required|numeric|min:3|max:60',
                'quantity'          => 'required|numeric|min:1|max:10'
            ]);

            if($validator->fails()) {
                $errors = $validator->errors();
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    => $errors->all(),
                ]);
            }

            if($proPlayerSkill->status !== 2)
                throw new Exception('Unprocessable, Pro player skill not valid', 422);

            $userId  = auth()->user()->id;
            $user    = User::with(['player', 'coinSendingTransactions'])->find($userId);
            $player  = $user->player;
            $price   = $proPlayerSkill->price_permatch;
            $revenue = $proPlayerSkill->pro_player_price;
            $orders  = $player->proPlayerOrders;

            if($proPlayerSkill->player->id === $player->id)
                throw new Exception('Unprocessable, Unable to order your own skill', 422);

            if($proPlayerSkill->activity === 0)
                throw new Exception('Unprocessable, Unable to order offline pro player skill', 422);

            if( $orders
                ->where('pro_player_skill_id', $proPlayerSkill->id)
                ->where('status', 0)
                ->first()
            ) throw new Exception('Unprocessable, You have ordered this skill', 422);

            if($player->coin < $price['coin'])
                throw new Exception('Unprocessable, Not enough coins', 422);

            $order = ProPlayerOrder::create([
                'player_id'             => $player->id,
                'pro_player_skill_id'   => $proPlayerSkill->id,
                'coin'                  => $revenue['coin'],
                'balance'               => $revenue['balance'],
                'quantity'              => $request->quantity,
                'play_duration'         => 30 * $request->quantity,
                'expiry_duration'       => intval($request->expiry_duration),
                'status'                => 0
            ]);

            $player
                ->update([
                    'coin'  => $player->coin - $price['coin'],
                ]);

            $user
                ->coinSendingTransactions()
                ->create([
                    'receiver_id'   => $order->proPlayerSkill->player->user->id,
                    'coin'          => -$price['coin'],
                    'balance'       => -$price['balance'],
                    'type'          => 1,
                    'status'        => 'success'
                ]);

            // SEND PUSH NOTIFICATION
            $recipients = Arr::flatten(
                $proPlayerSkill
                ->player
                ->user
                ->deviceIds()
                ->select('device_id')
                ->get()
                ->makeHidden('status_name')
                ->toArray()
            );

            $payloads = [
                'title' => 'Ada Orderan Nih !',
                'body'  => "Orderan game [{$proPlayerSkill->game->name}] dari pemain ({$user->username})"
            ];

            fcm()->to($recipients) // Must an array
            ->timeToLive($order->expiry_duration * 60) // In seconds
            ->data($payloads)
            ->notification($payloads)
            ->send();

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


    public function unorder(ProPlayerSkill $proPlayerSkill) {
        try{
            $userId = auth()->user()->id;
            $user   = User::with(['player.proPlayerOrders.proPlayerSkill.game'])->find($userId);
            $player = $user->player;
            $price  = $proPlayerSkill->price_permatch;
            $order  = $player->proPlayerOrders()
                ->where('pro_player_skill_id', $proPlayerSkill->id)
                ->where('status', 0)
                ->first();

            if(!$order)
                throw new Exception("Unprocessable, No pending orders", 422);

            $proPlayerSkill->load([
                'game',
                'player.user',
            ]);

            $order->update([
                'status' => 3,
            ]);

            $player->update([
                'coin'  => $player->coin + $price['coin']
            ]);

            $user
                ->coinReceivingTransactions()
                ->create([
                    'coin'      => $price['coin'],
                    'balance'   => $price['balance'],
                    'type'      => 2,
                    'status'    => 'success'
                ]);

            // SEND PUSH NOTIFICATION
            $recipients = Arr::flatten([
                $user
                ->deviceIds()
                ->select('device_id')
                ->get()
                ->makeHidden('status_name')
                ->toArray()
            ]);

            $payloads = [
                'title' => 'Order berhasil dibatalkan',
                'body'  => "Orderan {$proPlayerSkill->player->user->username} game [{$proPlayerSkill->game->name}] berhasil anda batalkan"
            ];

            fcm()->to($recipients) // Must an array
            ->timeToLive(120) // In seconds
            ->data($payloads)
            ->notification($payloads)
            ->send();
            
            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $order,
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


    public function endOrder(ProPlayerSkill $proPlayerSkill) {
        try{
            $userId = auth()->user()->id;
            $user   = User::with(['player.proPlayerOrders.proPlayerSkill'])->find($userId);
            $player = $user->player;
            $order  = $player->proPlayerOrders()
                ->where('pro_player_skill_id', $proPlayerSkill->id)
                ->where('status', 2)
                ->latest()
                ->first();

            if(!$order)
                throw new Exception('Unprocessable, this order is not an approved order', 422);

            $order->update([
                'status'    => 4,
                'ended_at'  => now(),
            ]);

            // ADDING PRO PLAYER BALANCE
            $proPlayer = $order->proPlayerSkill->player;

            $proPlayer
                ->update([
                    'balance'   => $order->balance
                ]);

            $proPlayer
                ->user
                ->balanceReceivingTransactions()
                ->create([
                    'sender_id' => $userId,
                    'coin'      => $order->coin,
                    'balance'   => $order->balance,
                    'type'      => 2,
                    'status'    => 'success'
                ]);

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => ProPlayerOrderResource::make($order),
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ?? 400;
            $errMessage = $err->getMessage();
            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ]);
        }
    }


    public function reviews(ProPlayerSkill $proPlayerSkill) {
        $reviews = $proPlayerSkill
            ->proPlayerOrderReviews()
            ->with('proPlayerOrder.player.user')
            ->paginate(10);

        return response()->json(
            collect([
                'status'    => 200,
                'message'   => 'OK',
            ])
            ->merge($reviews)
            ->merge(['data' => ProPlayerOrderReviewResource::collection($reviews)])
        );
    }
}
