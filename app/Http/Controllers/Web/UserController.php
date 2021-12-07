<?php

namespace App\Http\Controllers\Web;

use App\Helpers\UsernameHelper;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
    public function index() {
        $users = User::paginate(10);

        return view('pages.admin.users.index', compact('users'));
    }


    public function destroy($userId) {
        try{
            $user = User::find($userId);

            if(!$user)
                throw new Exception('User not found', 404);

            $user->delete();

            Alert::success('Success', 'User deleted successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Success', $errMessage);
        }finally{
            return back();
        }
    }


    public function storeAdmin(Request $request) {
        try{
            $request->validate([
                'name'  => 'bail|required|min:2|max:30|regex:/[a-z ]*/i',
                'email' => 'required|email|unique:users',
            ]);

            // Create Account
            $emailName = explode('@', $request->email)[0];
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'username'  => UsernameHelper::make($emailName),
                'password'  => Hash::make('password'),
            ]);

            // Create Admin
            $user->admin()->create();

            Alert::success('Success', 'Admin added successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
