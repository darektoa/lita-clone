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
            $user   = auth()->user();
            $request->validate([
                'name'              => 'bail|required|min:2|max:30|regex:/[a-z ]*/i',
                'username'          => "required|required|regex:/[0-9a-z\._]{5,15}/i|unique:users,username,{$user->id}",
                'email'             => 'required|email',
                'new_password'      => 'nullable|min:5|max:16',
                'confirm_password'  => 'same:new_password'
            ]);
            $currPassword   = $request->current_password;
            $newPassword    = $request->new_password;

            if($currPassword && !(Hash::check($currPassword, $user->password)))
                throw new Exception('Current password is wrong');

            $user       = User::find($user->id);
            $updateData = [
                'name'      => $request->name,
                'username'  => $request->username,
                'email'     => $request->email,
            ];

            if($newPassword) $updateData['password'] = Hash::make($newPassword);

            $user->update($updateData);

            Alert::success('Success', 'Profile updated successfully');
            return back();
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
            return back()->with('errors', []);
        }
    }
}
