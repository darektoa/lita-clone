<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\{ResponseHelper, UsernameHelper};
use App\Models\{ User, LoginToken };
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) {
        $email = $request->email;
        $password = $request->password;

        if(
            Auth::attempt(['email' => $email, 'password' => $password]) ||
            Auth::attempt(['username' => $email, 'password' => $password])
        ) {
            $loginToken = LoginToken::firstOrCreate(
                ['user_id' => auth()->user()->id],
                ['token' => Hash::make(auth()->user()->id)]
            );

            $user   = User::find(auth()->user()->id);
            
            if(!$user->sso_type && $request->sso_type) $user->update([
                'sso_type'  => $request->sso_type,
            ]);
            
            if(!$user->sso_id && $request->sso_id) $user->update([
                'sso_id'  => $request->sso_id,
            ]);

            $user->token = $loginToken->token;

            return ResponseHelper::make(
                UserResource::make($user),
            );
        } else {
            return ResponseHelper::error(
                ["Account doesn't match"],
                "Unauthorized, Account doesn't match",
                401
            );
        }
    }


    public function loginSSO(Request $request) {
        $user       = User::where('email', $request->email)->first();
        $login      = $this->login($request);
        $register   = $this->register($request);

        if(isset($login->getData()->token)) return $login;
        if(isset($register->getData()->token)) return $register;

        if($user) return $login;
        return $register;
    }


    public function logout(Request $request) {
        try{
            $token = LoginToken::where('token', $request->token)->first();
    
            if(!$token) throw new ErrorException('Unauthorized Token', 401, [
                'Unauthorized Token'
            ]);
    
            $token->delete();
            return ResponseHelper::make([], 'OK, Logout Success');
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
    


    public function register(Request $request) {
        $isSSO     = $request->is('api/login/sso');
        $validator = Validator::make($request->all(), [
            'name'      => 'bail|required|min:2|max:30|regex:/[a-z ]*/i',
            'email'     => 'required|email|unique:users',
            'password'  => $isSSO ? 'required|min:5'   : 'required|min:5|max:16',
            'sso_id'    => $isSSO ? 'required|max:255' : 'nullable|max:0',
            'sso_type'  => $isSSO ? 'required|max:50'  : 'nullable|max:0',
        ]);

        $errors = $validator->errors();
        if($validator->fails()) {
            return response()->json([
                'message' => 'invalid field',
                'errors' => $errors->all()
            ], 422);
        }

        // Create Account
        $emailName = explode('@', $request->email)[0];
        $user = User::create([
            'name'      => $request->name,
            'username'  => UsernameHelper::make($emailName),
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'sso_id'    => $request->sso_id,
            'sso_type'  => $request->sso_type,
        ]);

        //Create Player
        $user->player()->create();

        
        // Login User
        $userId     = $user->id;
        $user       = User::find($userId);
        $loginToken = LoginToken::create([
            'user_id' => $userId,
            'token'   => Hash::make($userId),
        ]);

        $user->token = $loginToken->token;
        return ResponseHelper::make(
            UserResource::make($user)
        );
    }
}
