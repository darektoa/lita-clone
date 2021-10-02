<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        return redirect('/dashboard');
    }


    public function loginView() {
        return view('pages.auth.login');
    }


    public function register(Request $request) {


        return redirect('/login');
    }


    public function registerView() {
        return view('pages.auth.register');
    }
}
