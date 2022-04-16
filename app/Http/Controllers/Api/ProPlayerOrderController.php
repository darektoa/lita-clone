<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\ResponseHelper;
use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProPlayerOrderResource;
use App\Http\Resources\ReportResource;
use App\Models\ProPlayerOrder;
use App\Models\Report;
use App\Notifications\PushNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Notification, Validator};

class ProPlayerOrderController extends Controller
{
    public function index(Request $request) {
        $player   = auth()->user()->player;
        $status   = $request->status;
        $statuses = explode(',', $status);
        $orders   = ProPlayerOrder::with([
            'review',
            'proPlayerService.service',
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
    }


    public function proIndex(Request $request) {
        $player     = auth()->user()->player;
        $status     = $request->status;
        $statuses   = explode(',', $status);
        $orders     = ProPlayerOrder::with([
            'review',
            'player.user',
            'proPlayerSkill.game',
            'proPlayerService.service',
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
            $player     = auth()->user()->player;
            $orderable  = $proPlayerOrder->proPlayerSkill ?? $proPlayerOrder->proPlayerService;

            if($orderable->player_id !== $player->id)
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

            $service  = $orderable->game ?? $orderable->service;
            $payloads = [
                'title'      => 'Ayo main, order di terima !',
                'body'       => "{$player->user->username} menerima orderan [{$service->name}] anda",
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
            $orderable  = $proPlayerOrder->proPlayerSkill ?? $proPlayerOrder->proPlayerService;
            $price      = $orderable->price_permatch;

            if($orderable->player_id !== $player->id)
                throw new Exception('Not found', 404);
            if($proPlayerOrder->status !== 0)
                throw new Exception('Unprocessable, Order is not pending', 422);
            
            $proPlayerOrder->update([
                'status'            => 1,
                'rejected_reason'   => $request->reason,
            ]);

            $orderer->user
                ->coinSendingTransactions()
                ->where('type', 1)->where('status', 'pending')
                ->oldest()->first()->update([
                    'status'    => 'rejected'
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
            $recipients = $proPlayerOrder
                ->player
                ->user;

            $service  = $orderable->game ?? $orderable->service;
            $payloads = [
                'title'      => 'Maaf, order kamu di tolak',
                'body'       => "{$user->username}[{$service->name}]: \"{$proPlayerOrder->rejected_reason}\"",
                'timeToLive' => 2419200,
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

            if($proPlayerOrder->player_id !== auth()->user()->player->id)
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
            $orderable = $proPlayerOrder->proPlayerSkill ?? $proPlayerOrder->proPlayerService;
            $orderable->updateRate();
            $orderable->player->updateRate();

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
    
    
    public function report(Request $request, ProPlayerOrder $proPlayerOrder) {
        try{
            $validator = Validator::make($request->all(), [
                'report' => 'required',
                'proof'  => 'nullable|file'
            ]);

            $reportData = [
                'reporter_id'   => auth()->id(),
                'report'        => $request->report,
                'type'          => 2,
                'status'        => 0,
            ];

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable', 422, $errors);
            }

            if($proPlayerOrder->player_id !== auth()->user()->player->id)
                throw new ErrorException('Not allowed, This is not your order', 403);

            if($proPlayerOrder->status !== 4) // 4 = Ended Status
                throw new ErrorException('Unprocessable, This is not a finished order', 422);

            if($proPlayerOrder->report)
                throw new ErrorException('Unprocessable, You have reported the order', 422);

            if($request->proof) {
                $proofPath = StorageHelper::put('media/reports', $request->proof);
                $reportData['proof'] = $proofPath;
            }

            $report = $proPlayerOrder->report()->create($reportData);

            return ResponseHelper::make(
                ReportResource::make($report)
            );
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
