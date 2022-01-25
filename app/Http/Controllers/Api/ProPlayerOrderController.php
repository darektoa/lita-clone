<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProPlayerOrderResource;
use App\Models\ProPlayerOrder;
use App\Notifications\PushNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Notification, Validator};

class ProPlayerOrderController extends Controller
{
    public function index(Request $request) {
        try{
            $player   = auth()->user()->player;
            $status   = $request->status;
            $statuses = explode(',', $status);
            $orders   = ProPlayerOrder::with([
                'review',
                'proPlayerSkill.game',
                'proPlayerSkill.tier',
                'proPlayerSkill.player.user',
            ]);
                
            if($status !== null)
                $orders = $orders->whereIn('status', $statuses);
    
            $orders = $orders->where('player_id', $player->id)
                ->latest()
                ->paginate(10);
    
            return response()->json(
                collect([
                    'status'    => 200,
                    'message'   => 'OK'
                ])
                ->merge($orders)
                ->merge(['data' => ProPlayerOrderResource::collection($orders)])
            );
        }catch(Exception $err) {dd($err);}
    }


    public function proIndex(Request $request) {
        $player     = auth()->user()->player;
        $status     = $request->status;
        $statuses   = explode(',', $status);
        $orders     = ProPlayerOrder::with([
            'review',
            'player.user',
            'proPlayerSkill.game'
        ]);


        if($status !== null)
            $orders = $orders->whereIn('status', $statuses);

        $orders = $orders->whereRelation('proPlayerSkill', 'player_id', $player->id)
            ->latest()
            ->paginate();

        return response()->json(
            collect([
                'status'    => 200,
                'message'   => 'OK',
            ])
            ->merge($orders)
            ->merge(['data' => ProPlayerOrderResource::collection($orders)])
        );
    }


    public function approve(ProPlayerOrder $proPlayerOrder) {
        try{
            $player         = auth()->user()->player;
            $proPlayerSkill = $proPlayerOrder->proPlayerSkill; 

            if($proPlayerSkill->player_id !== $player->id)
                throw new Exception('Not found', 404);
            if($proPlayerOrder->status !== 0)
                throw new Exception('Unprocessable, Order is not pending', 422);
            
            $proPlayerOrder->update([
                'status'    => 2,
            ]);

            // SEND PUSH NOTIFICATION
            $recipients = $proPlayerOrder
                ->player
                ->user;

            $payloads = [
                'title'      => 'Ayo main, order di terima !',
                'body'       => "{$player->user->username} menerima orderan game [{$proPlayerSkill->game->name}] anda",
                'timeToLive' => $proPlayerOrder->play_duration *60,
            ];

            Notification::send($recipients, new PushNotification($payloads));

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $proPlayerOrder
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


    public function reject(Request $request, ProPlayerOrder $proPlayerOrder) {
        try{
            $validator = Validator::make($request->all(), [
                'reason'    => 'required|min:5|max:50'
            ]);

            if($validator->fails())
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    =>  $validator->errors()->all()
                ], 422);

            $user       = auth()->user();
            $player     = $user->player;
            $orderer    = $proPlayerOrder->player;
            $skill      = $proPlayerOrder->proPlayerSkill;
            $price      = $skill->price_permatch;

            if($skill->player_id !== $player->id)
                throw new Exception('Not found', 404);
            if($proPlayerOrder->status !== 0)
                throw new Exception('Unprocessable, Order is not pending', 422);
            
            $proPlayerOrder->update([
                'status'            => 1,
                'rejected_reason'   => $request->reason,
            ]);

            // COIN REFUND TO PLAYER
            $orderer
                ->user
                ->coinReceivingTransactions()
                ->create([
                    'coin'      => $price['coin'],
                    'balance'   => $price['balance'],
                    'type'      => 2,
                    'status'    => 'success'
                ]);

            $orderer->update([
                'coin'  => $orderer->coin + $price['coin'] 
            ]);
            
            // SEND PUSH NOTIFICATION
            $recipients = Arr::flatten([
                $proPlayerOrder
                ->player
                ->user
                ->deviceIds()
                ->select('device_id')
                ->get()
                ->makeHidden('status_name')
                ->toArray()
            ]);

            $payloads = [
                'title' => 'Maaf, order kamu di tolak',
                'body'  => "{$user->username}[{$proPlayerOrder->proPlayerSkill->game->name}]: \"{$proPlayerOrder->rejected_reason}\"",
            ];

            fcm()->to($recipients) // Must an array
            ->timeToLive(2419200) // 28 days
            ->data($payloads)
            ->notification($payloads)
            ->send();

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $proPlayerOrder
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


    public function review(Request $request, ProPlayerOrder $proPlayerOrder) {
        try{
            $validator = Validator::make($request->all(), [
                'star'   => 'required|numeric|min:1|max:5',
                'review' => 'required'
            ]);

            if($validator->fails())
                return response()->json([
                    'status'    => 422,
                    'message'   => 'Unprocessable, Invalid field',
                    'errors'    => $validator->errors(),
                ]);

            if($proPlayerOrder->player_id === auth()->user()->id)
                throw new Exception('Not allowed, This is not your order', 403);

            if($proPlayerOrder->status !== 4) // 4 = Ended Status
                throw new Exception('Unprocessable, This is not a finished order', 422);

            if($proPlayerOrder->review)
                throw new Exception('Unprocessable, You have reviewed the order', 422);

            $review = $proPlayerOrder->review()->create([
                'star'      => $request->star,
                'review'    => $request->review,
            ]);

            // UPDATE PRO PLAYER RATE
            $proPlayerOrder->proPlayerSkill->updateRate();
            $proPlayerOrder->proPlayerSkill->player->updateRate();

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $review
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
