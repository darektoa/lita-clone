<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\{ResponseHelper, StorageHelper};
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Traits\FCMTrait;
use Illuminate\Http\Request;
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
        $validator = Validator::make($request->all(), [
            'name'          => 'bail|required|min:2|max:30|regex:/[a-z ]*/i',
            'username'      => 'required|regex:/^[0-9a-z\._]{5,15}$/i|unique:username_exceptions,username|unique:users,username,'.$user->id,
            'email'         => 'required|email|unique:users,email,'.$user->id,
            'gender_id'     => 'required|exists:genders,id',
            'profile_photo' => 'nullable|image|max:10240',
            'cover_photo'   => 'nullable|image:max:10240',
            'birthday'      => 'nullable|date',
            'bio'           => 'nullable|max:255',
            'phone'         => 'required|numeric|min:10',
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

        if($user->profile_photo && $profilePhoto) StorageHelper::delete($user->profile_photo);
        if($user->cover_photo && $coverPhoto) StorageHelper::delete($user->cover_photo);
        if($user->voice && $voice) StorageHelper::delete($user->cover_photo);
        if($profilePhoto) $profilePhotoPath = StorageHelper::put('images/users/profiles', $profilePhoto);
        if($coverPhoto) $coverPhotoPath = StorageHelper::put('images/users/covers', $coverPhoto);
        if($voice) $voicePath = StorageHelper::put('audios/users/voices', $voice);


        // UPDATE USER DATA
        $user       = User::find($user->id);
        $updateData = [
            'name'      => $request->name,
            'username'  => $request->username,
            'email'     => $request->email,
            'gender_id' => $request->gender_id,
            'birthday'  => $request->birthday,
            'bio'       => $request->bio,
            'phone'     => $request->phone,
        ];

        if($profilePhoto) $updateData['profile_photo'] = $profilePhotoPath;
        if($coverPhoto) $updateData['cover_photo'] = $coverPhotoPath;

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


    public function changePassword(Request $request) {
        try{
            $user      = User::find(auth()->id());
            $isSSO     = (boolean) $user->sso_type;
            
            if($isSSO) throw new ErrorException('Forbidden', 403, [
                "Register with SSO account can't change password"
            ]);
    
            $validator = Validator::make($request->all(), [
                'old_password'  => 'required',
                'new_password'  => 'required|min:5|max:16'
            ]);

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('invalid field',422, $errors);
            }

            $Password    = $user->password;
            $oldPassword = $request->old_password;
            $newPassword = $request->new_password;
            
            if(!Hash::check($oldPassword, $Password)) throw new ErrorException('Unprocessable', 422, [
                "Old password doesn't match"
            ]);

            $user->update([
                'password'  => Hash::make($newPassword)
            ]);

            return ResponseHelper::make();
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
