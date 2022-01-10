<?php

namespace App\Http\Controllers\Api;

use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\FCMTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{Hash, Validator};

class ProfileController extends Controller
{
    use FCMTrait;

    public function index() {
        $user   = User::with([
            'player',
            'player.proPlayerSkills'
        ])->find(auth()->user()->id);

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => new UserResource($user),
        ]);
    }


    public function update(Request $request) {
        $user      = auth()->user();
        $isSSO     = Str::length($user->password) > 255;
        $validator = Validator::make($request->all(), [
            'name'          => 'bail|required|min:2|max:30|regex:/[a-z ]*/i',
            'username'      => 'required|regex:/^[0-9a-z\._]{5,15}$/i|unique:username_exceptions,username|unique:users,username,'.$user->id,
            'email'         => 'required|email|unique:users,email,'.$user->id,
            'password'      => $isSSO ? 'exclude' : 'nullable|min:5|max:16',
            'gender_id'     => 'nullable|exists:genders,id',
            'profile_photo' => 'nullable|image|max:10240',
            'cover_photo'   => 'nullable|image:max:10240',
            'birthday'      => 'nullable|date',
            'bio'           => 'max:255',
            'voice'         => 'nullable|mimes:mp3,m4a,aac,ogg',
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
        $user       = User::find($user->id);
        $password   = $request->password;
        $updateData = [
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'gender_id' => $request->gender_id,
            'birthday'  => $request->birthday,
            'bio'       => $request->bio,
        ];

        if($profilePhoto) $updateData['profile_photo'] = $profilePhotoPath;
        if($coverPhoto) $updateData['cover_photo'] = $coverPhotoPath;
        if($password) $updateData['password'] = Hash::make($password);

        $user->update($updateData);
        $user->player->update([
            'voice' => $voicePath,
        ]);
        
        return [
            'status'    => 200,
            'message'   => 'OK',
            'data'      => new UserResource($user)
        ];
    }
}
