<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\{DeviceId, User};
use App\Notifications\PushNotification;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\{Notification, Validator};

class NotificationController extends Controller
{
    public function index() {
        $id             = auth()->id();
        $user           = User::with(['notifications'])->find($id);
        $notifications  = $user->notifications()->paginate(10);

        return ResponseHelper::paginate(
            NotificationResource::collection($notifications)
        );
    }


    public function store(Request $request) {
        $user   = auth()->user();

        $deviceId = DeviceId::firstOrCreate(
            [
                'device_id' => $request->device_id,
                'user_id'   => $user->id ?? null,
            ],
            ['status'    => 1]
        );

        $deviceId->update([
            'status' => 1
        ]);

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => $deviceId,
        ]);
    }


    public function unsubscribe() {
        try{
            $user       = auth()->user();
            $deviceIds  = DeviceId::where('user_id', $user->id);

            $deviceIds->update([
                'status' => 0
            ]);
            
            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $deviceIds->get()
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


    public function send(Request $request) {
        try{
            $validator  = Validator::make($request->all(), [
                'user_id'   => 'required|numeric|exists:users,id',
                'title'     => 'required|string'
            ]);

            if($validator->fails()) return response()->json([
                'status'    => 422,
                'message'   => 'Unprocessable, Invalid field',
                'errors'    => $validator->errors()->all(),
            ], 422);
            
            $user      = User::find($request->user_id);
            $recipient = Arr::flatten([
                $user
                ->deviceIds()
                ->select('device_id')
                ->get()
                ->makeHidden('status_name')
                ->toArray()
            ]);

            $payloads = [
                'title' => $request->title,
                'body'  => $request->body,
            ];

            fcm()->to($recipient)
            ->timeToLive(86400) // 1 day
            ->data($payloads)
            ->notification($payloads)
            ->send();

            return response()->json([
                'status'    => 200,
                'message'   => 'OK',
                'data'      => $request->all(),
            ]);
        }catch(Exception $err) {
            $errCode    = $err->getCode() ? 400 : 400;
            $errMessage = $err->getMessage();

            return response()->json([
                'status'    => $errCode,
                'message'   => $errMessage,
            ], $errCode);
        }
    }
}
