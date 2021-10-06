<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UsernameHelper;
use App\Models\{ User, LoginToken };
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        $username = $request->username;
        $password = $request->password;

        if(
            Auth::attempt(['email' => $username, 'password' => $password]) ||
            Auth::attempt(['username' => $username, 'password' => $password])
        ) {
            $loginToken = LoginToken::create([
                'user_id' => auth()->user()->id,
                'token' => Hash::make(auth()->user()->id)
            ]);

            return response()->json(['token' => $loginToken->token]);
        } else {
            return response()->json(['message'=> 'invalid login'], 401);
        }
    }
    


    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name'        => 'bail|required|alpha|min:2|max:20',
            'last_name'         => 'required|alpha|min:2|max:20',
            'email'             => 'required|email|unique:users',
            'password'          => 'required|min:5|max:16',
            'password_confirm'  => 'required|same:password'
        ]);

        $errors = $validator->errors();
            // dd($errors);
        if($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
                'errors' => $errors['email']
            ], 422);
        }

        // Create Account
        $emailName = explode('@', $request->email)[0];
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'username'   => UsernameHelper::make($emailName),
            'email'      => $request->email,
            'password'   => Hash::make($request->password)
        ]);

        // Login User
        $userId = $user->id;
        $loginToken = LoginToken::create([
            'user_id' => $userId,
            'token'   => Hash::make($userId),
        ]);

        return response()->json([
            'token'=> $loginToken->token
        ], 200);
    }
}
