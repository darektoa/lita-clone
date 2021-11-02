<?php

namespace App\Http\Controllers\Web;

use App\Helpers\UsernameHelper;
use App\Http\Controllers\Controller;
use App\Models\{User, Admin, Player};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request) {
        $username = $request->username;
        $password = $request->password;

        if(
            Auth::attempt(['email' => $username, 'password' => $password]) ||
            Auth::attempt(['username' => $username, 'password' => $password])
        ) return redirect('/dashboard');

        return back()->withErrors(["Account doesn't match"]);
    }


    public function loginView() {
        return view('pages.auth.login');
    }


    public function logout() {
        Auth::logout();
        return redirect('/login');
    }


    public function register(Request $request) {
        $request->validate([
            'name'              => 'bail|required|min:2|max:30|regex:/[a-z ]*/i',
            'email'             => 'required|email|unique:users',
            'password'          => 'required|min:5|max:16',
            'password_confirm'  => 'required|same:password'
        ]);

        $emailName = explode('@', $request->email)[0];

        $user = User::create([
            'name'       => $request->name,
            'username'   => UsernameHelper::make($emailName),
            'email'      => $request->email,
            'password'   => Hash::make($request->password)
        ]);

        Player::create([
            'user_id' => $user->id,
        ]);

        return redirect('/login');
    }


    public function registerView() {
        return view('pages.auth.register');
    }
}
