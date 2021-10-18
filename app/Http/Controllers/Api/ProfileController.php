<?php

namespace App\Http\Controllers\Api;

use App\Helpers\StorageHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Hash, Storage, Validator};

class ProfileController extends Controller
{
    public function update(Request $request) {
        $isSSO     = auth()->user()->password->count() > 16;
        $validator = Validator::make($request->all(), [
            'first_name'        => 'bail|required|alpha|min:2|max:20',
            'last_name'         => 'required|alpha|min:2|max:20',
            'email'             => 'required|email|unique:users',
            'password'          => $isSSO ? 'exclude' : 'required|min:5|max:16',
            'profile_photo'     => 'image|max:10240',
            'cover_photo'       => 'image:max:10240',
            'birthday'          => 'date',
            'bio'               => 'max:255',
            'voice'             => 'file'
        ]);

        $errors = $validator->errors();
        if($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
                'errors' => $errors->all()
            ], 422);
        }

        $profilePhotoPath   = StorageHelper::put('images/profile-photos', $request->profile_photo);
        $coverPhotoPath     = StorageHelper::put('images/cover-photos', $request->cover_photo);

        $user = auth()->user->update([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'username'      => $request->username,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'profile_photo' => $profilePhotoPath,
            'cover_photo'   => $coverPhotoPath,
            'birthday'      => $request->birthday,
            'bio'           => $request->bio,
        ])->player()->update([
            'voice' => $request->voice,
        ]);

        return response()->json([
            'data' => $user,
        ], 200);
    }
}
