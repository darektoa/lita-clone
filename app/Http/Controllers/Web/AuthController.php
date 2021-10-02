<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        return redirect('/dashboard');
    }


    public function loginView() {
        return view('pages.auth.login');
    }


    public function register() {
        return redirect('/login');
    }


    public function registerView() {
        return view('pages.auth.register');
    }
}
