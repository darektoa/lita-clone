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
}
