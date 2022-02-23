<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\{ResponseHelper, StorageHelper};
use App\Http\Controllers\Controller;
use App\Models\ChatMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatMediaController extends Controller
{
    public function store(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'media'     => 'required|file|max:30720', // MAX:30MB'
                'user_id'   => 'required|exists:users,id',
                'alt'       => 'nullable|max:1000',
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable, Invalid field', 422, $errors);
            }

            $mediaPath  = StorageHelper::put('media/chats', $request->media);
            $media      = ChatMedia::create([
                'sender_id'     => auth()->id(),
                'receiver_id'   => $request->user_id,
                'url'           => $mediaPath,
                'alt'           => $request->alt,
            ]);

            return ResponseHelper::make($media);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
