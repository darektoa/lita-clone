<?php

namespace App\Http\Controllers;

use App\Models\{
    User,
    LoginToken
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // REGISTER METHOD
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|alpha|min:2|max:20',
            'last_name' => 'required|alpha|min:2|max:20',
            'username' => 'required|alpha_num|min:5|max:12|unique:users',
            'password' => 'required|min:5|max:12'
        ]);

        $errors = $validator->errors();
            // dd($errors);
        if($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
                'errors' => $errors['username']
            ], 422);
        }

        // SIMPAN DATA USER
        $user = new User;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        $id_user = $user->id;

        // LOGIN USER
        $token = new LoginToken;
        $token->user_id = $id_user;
        $token->token = Hash::make($id_user);

        return response()->json([
            'Authorization-Token'=> $token->token
        ], 200);
    }


    // LOGIN METHOD
    public function login(Request $request) {
        $username = $request->username;
        $password = $request->password;

        if(Auth::attempt(['username' => $username, 'password' => $password])) {
            $token = new LoginToken;
            $token->user_id = Auth::user()->id;
            $token->token = Hash::make(Auth::user()->id);
            $token->save();

            return response()->json(['token' => $token->token]);
        } else {
            return response()->json(['message'=> 'invalid login', 401]);
        }
    }


    // LOGUT METHOD
    public function logout(Request $request) {
        $token = LoginToken::where('token', $request->token)->first();

        if($token) {
            $token->delete();
            return response()->json(['message'=>'logout success']);
        } else {
            return response()->json(['message'=>'unauthorized user']);
        }
    }
}
