<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\{ResponseHelper, StorageHelper};
use App\Http\Controllers\Controller;
use App\Models\{Chat, ChatMedia};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function store(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'media'         => 'nullable|file|max:30720', // MAX:30MB'
                'message'       => 'nullable|max:1000',
                'receiver_id'   => 'required|exists:users,id'
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable, Invalid field', 422, $errors);
            }

            $userId     = auth()->id();
            $media      = $request->media;
            $message    = $request->message;
            $receiverId = $request->receiver_id;

            if(!$media && !$message) throw new ErrorException('Unprocessable', 422, [
                'One of the media or message fields is required'
            ]);
            
            $chat = Chat::create([
                'id'            => Str::uuid(),
                'sender_id'     => $userId,
                'receiver_id'   => $receiverId,
                'message'       => $message,
            ]);

            if($media) {
                $mediaPath  = StorageHelper::put('media/chats', $request->media);
                ChatMedia::create([
                    'chat_id'   => $chat->id,
                    'url'       => $mediaPath,
                ]);
            }

            $chat->media;
            
            return ResponseHelper::make($chat);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
