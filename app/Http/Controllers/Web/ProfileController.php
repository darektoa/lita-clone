<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class ProfileController extends Controller
{
    public function index() {
        return view('pages.general.profile.index');
    }


    public function update(Request $request) {
        try{
            $user = User::find(auth()->id());
            $request->validate([
                'name'              => 'bail|nullable|min:2|max:30|regex:/[a-z ]*/i',
                'username'          => "nullable|regex:/[0-9a-z\._]{5,15}/i|unique:users,username,{$user->id}",
                'email'             => 'nullable|email',
                'new_password'      => 'nullable|min:5|max:16',
                'confirm_password'  => 'same:new_password'
            ]);
            $currPassword   = $request->current_password;
            $newPassword    = $request->new_password;

            if($currPassword && !(Hash::check($currPassword, $user->password)))
                throw new Exception('Current password is wrong');

            $user->update([
                'name'      => $request->name ?? $user->name,
                'username'  => $request->username ?? $user->username,
                'email'     => $request->email ?? $user->email,
                'password'  => $newPassword ? Hash::make($newPassword) : $user->password
            ]);

            Alert::success('Success', 'Profile updated successfully');
            return back();
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
            return back()->with('errors', []);
        }
    }
}
