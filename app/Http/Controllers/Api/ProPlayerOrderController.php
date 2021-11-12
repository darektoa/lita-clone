<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProPlayerOrderResource;
use App\Models\ProPlayerOrder;
use Exception;
use Illuminate\Http\Request;

class ProPlayerOrderController extends Controller
{
    public function index(Request $request) {
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
    }


    public function proIndex(Request $request) {
        $player     = auth()->user()->player;
        $orders     = ProPlayerOrder::with(['proPlayerSkill']);
        $status     = $request->status;


        if($status !== null && $status >= 0 && $status <= 3)
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
        );
    }


    public function approve(ProPlayerOrder $proPlayerOrder) {
        try{
            $player = auth()->user()->player;

            if($proPlayerOrder->proPlayerSkill->player_id !== $player->id)
                throw new Exception('Not found', 404);
            if($proPlayerOrder->status !== 0)
                throw new Exception('Unprocessable, Order is not pending', 422);
            
            $proPlayerOrder->update([
                'status'    => 2,
            ]);

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


    public function reject(ProPlayerOrder $proPlayerOrder) {
        try{
            $player = auth()->user()->player;

            if($proPlayerOrder->proPlayerSkill->player_id !== $player->id)
                throw new Exception('Not found', 404);
            if($proPlayerOrder->status !== 0)
                throw new Exception('Unprocessable, Order is not pending', 422);
            
            $proPlayerOrder->update([
                'status'    => 1,
            ]);

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
