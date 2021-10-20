<?php

namespace App\Http\Controllers\Api;

use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Hash, Validator};

class ProfileController extends Controller
{
    public function update(Request $request) {
        $user      = auth()->user();
        $isSSO     = Str::length($user->password) > 255;
        $validator = Validator::make($request->all(), [
            'first_name'        => 'bail|required|alpha|min:2|max:20',
            'last_name'         => 'required|alpha|min:2|max:20',
            'username'          => 'required|alpha_num|min:5|max:30',
            'email'             => 'required|email|unique:users,email,'.$user->id,
            'password'          => $isSSO ? 'exclude' : 'required|min:5|max:16',
            'profile_photo'     => 'image|max:10240',
            'cover_photo'       => 'image:max:10240',
            'birthday'          => 'date',
            'bio'               => 'max:255',
            'voice'             => 'file'
        ]);

        // VALIDATOR ERROR VALIDATION
        $errors = $validator->errors();
        if($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
                'errors' => $errors->all()
            ], 422);
        }


        // UPDATE FILE IN STORAGE
        $profilePhoto       = $request->profile_photo; 
        $coverPhoto         = $request->cover_photo;
        $voice              = $request->voice;
        $profilePhotoPath   = null; 
        $coverPhotoPath     = null;
        $voicePath          = null;

        if($user->profile_photo) StorageHelper::delete($user->profile_photo);
        if($user->cover_photo) StorageHelper::delete($user->cover_photo);
        if($user->voice) StorageHelper::delete($user->cover_photo);
        if($profilePhoto) $profilePhotoPath = StorageHelper::put('images/users/profiles', $profilePhoto);
        if($coverPhoto) $coverPhotoPath = StorageHelper::put('images/users/covers', $coverPhoto);
        if($voice) $voicePath = StorageHelper::put('audios/users/voices', $voice);


        // UPDATE USER DATA
        $user = User::find($user->id);
        $user->update([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'profile_photo' => $profilePhotoPath,
            'cover_photo'   => $coverPhotoPath,
            'birthday'      => $request->birthday,
            'bio'           => $request->bio,
        ]);
        
        $user->player->update([
            'voice' => $voicePath,
        ]);

        return new UserResource($user);
    }
}