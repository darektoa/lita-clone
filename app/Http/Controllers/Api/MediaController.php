<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoginToken;
use App\Exceptions\ErrorException;
use App\Helpers\ResponseHelper;
use App\Helpers\StorageHelper;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function store(Request $request) {
        try{
            $media  = $request->upload;
            $token  = $request->token;
            $token  = LoginToken::with('user.admin')
                ->whereToken($token)
                ->whereHas('user', function($user) {
                    $user->whereHas('admin');
                })->first();

            if(!$token) throw new ErrorException('Unauthorized', 401);

            $path   = StorageHelper::put('media/other', $media);
            $url    = StorageHelper::url($path);
                
            return response()->json([
                'uploaded'  => true,
                'url'       => $url,
            ], 200);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
