<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProPlayerOrderResource;
use App\Models\ProPlayerOrder;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class ProPlayerOrderController extends Controller
{
    public function index(Request $request) {
        try{

            $player = auth()->user()->player;
            $status = $request->status;
            $orders = ProPlayerOrder::with([
                'proPlayerSkill.game',
                'proPlayerSkill.tier',
                'proPlayerSkill.player.user'
            ]);
                
            if($status !== null && $status >= 0 && $status <= 5)
                $orders = $orders->where('status', $status);
    
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
        $orders     = ProPlayerOrder::with([
            'player.user',
            'proPlayerSkill.game'
        ]);


        if($status !== null && $status >= 0 && $status <= 5)
            $orders = $orders->where('status', $status);

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
            $receipients = Arr::flatten(
                $proPlayerOrder
                ->player
                ->user
                ->deviceIds()
                ->select('device_id')
                ->get()
                ->makeHidden('status_name')
                ->toArray()
            );

            $payloads = [
                'title' => 'Ayo main, order di terima !',
                'body'  => "{$player->user->username} menerima orderan game [{$proPlayerSkill->game->name}] anda"
            ];
 
            fcm()->to($receipients) // Must an array
            ->timeToLive($proPlayerOrder->play_duration *60) // In seconds
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

            $user   = auth()->user();
            $player = $user->player;

            if($proPlayerOrder->proPlayerSkill->player_id !== $player->id)
                throw new Exception('Not found', 404);
            if($proPlayerOrder->status !== 0)
                throw new Exception('Unprocessable, Order is not pending', 422);
            
            $proPlayerOrder->update([
                'status'            => 1,
                'rejected_reason'   => $request->reason,
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
}
