<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
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
}
