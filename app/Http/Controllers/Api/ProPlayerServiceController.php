<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\{CollectionHelper, ResponseHelper};
use App\Http\Controllers\Controller;
use App\Http\Resources\ProPlayerServiceResource;
use App\Models\{ProPlayerOrder, ProPlayerService, User};
use App\Notifications\PushNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, Notification};

class ProPlayerServiceController extends Controller
{
    public function index() {
        $user       = auth()->user() ?? null;
        $userGender = $user->gender->id ?? null;
        $services   = ProPlayerService::with(['player.user.gender', 'service'])
            ->where('status', 2);

        if($userGender === 1){
            $services = $services->get()->sortByDesc(fn($query) => (
                $query->player->user->gender_id === 2 // Female
            ));
            $services = CollectionHelper::paginate($services, 10);
        } else {
            $services = $services->get()->shuffle();
        }

        return ResponseHelper::make(
            ProPlayerServiceResource::collection($services)
        );
    }


    public function order(Request $request, ProPlayerService $proPlayerService) {
        try{
            $validator = Validator::make($request->all(), [
                'expiry_duration'   => 'required|numeric|min:3|max:60',
                'quantity'          => 'required|numeric|min:1|max:10'
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable', 422, $errors);
            }

            if($proPlayerService->status !== 2)
                throw new ErrorException('Unprocessable, Pro player service not valid', 422);

            $userId  = auth()->user()->id;
            $user    = User::with(['player.proPlayerOrders', 'coinSendingTransactions'])->find($userId);
            $player  = $user->player;
            $price   = $proPlayerService->price_permatch;
            $revenue = $proPlayerService->pro_player_price;
            $orders  = $player->proPlayerOrders;

            if($proPlayerService->player->id === $player->id)
                throw new ErrorException('Unprocessable, Unable to order your own service', 422);

            if($proPlayerService->activity === 0)
                throw new ErrorException('Unprocessable, Unable to order offline pro player service', 422);

            if( $orders
                ->where('pro_player_service_id', $proPlayerService->id)
                ->where('status', 0)
                ->first()
            ) throw new ErrorException('Unprocessable, You have ordered this service', 422);

            if($player->coin < $price['coin'])
                throw new ErrorException('Unprocessable, Not enough coins', 422);

            $order = ProPlayerOrder::create([
                'player_id'             => $player->id,
                'pro_player_service_id' => $proPlayerService->id,
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
                    'receiver_id'   => $order->proPlayerService->player->user->id,
                    'coin'          => -$price['coin'],
                    'balance'       => -$price['balance'],
                    'type'          => 1,
                    'status'        => 'pending'
                ]);

            // SEND PUSH NOTIFICATION
            $recipients = $proPlayerService
                ->player
                ->user;

            $payloads = [
                'title'      => 'Ada Orderan Nih !',
                'body'       => "Orderan service [{$proPlayerService->service->name}] dari pemain ({$user->username})",
                'timeToLive' => $order->expiry_duration * 60,
            ];

            Notification::send($recipients, new PushNotification($payloads));

            return ResponseHelper::make(
                $order
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
