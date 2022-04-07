<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\{ResponseHelper, StorageHelper};
use App\Http\Controllers\Controller;
use App\Http\Resources\ChatMediaResource;
use App\Models\ChatMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatMediaController extends Controller
{
    public function store(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'media'     => 'required|file|max:30720', // MAX:30MB'
                'alt'       => 'nullable|max:1000',
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable, Invalid field', 422, $errors);
            }

            $mediaPath  = StorageHelper::put('media/chats', $request->media);
            $media      = ChatMedia::create([
                'url'           => $mediaPath,
                'alt'           => $request->alt,
            ]);

            return ResponseHelper::make(
                ChatMediaResource::make($media)
            );
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }


    public function show(ChatMedia $chatMedia) {
        try{
            $userId     = auth()->id();
            $senderId   = $chatMedia->sender->id;
            $receiverId = $chatMedia->receiver->id;

            if($senderId !== $userId && $receiverId !== $userId) throw new ErrorException(
                'Not found', 404, ['Not Found']
            );

            $chatMedia->unsetRelations();

            return ResponseHelper::make(
                ChatMediaResource::make($chatMedia)
            );
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
    
    
    public function destroy(ChatMedia $chatMedia) {
        try{
            $userId     = auth()->id();
            $senderId   = $chatMedia->sender->id;
            $receiverId = $chatMedia->receiver->id;

            if($senderId !== $userId && $receiverId !== $userId) throw new ErrorException(
                'Not found', 404, ['Not Found']
            );

            StorageHelper::delete($chatMedia->url);
            $chatMedia->delete();
            $chatMedia->unsetRelations();
            
            return ResponseHelper::make(
                ChatMediaResource::make($chatMedia)
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
