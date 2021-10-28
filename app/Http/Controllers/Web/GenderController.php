<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Gender;
use Exception;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class GenderController extends Controller
{
    public function index() {
        $genders = Gender::paginate(10);

        return view('pages.admin.setting.genders.index', compact('genders'));
    }


    public function store(Request $request) {
        try{
            $request->validate([
                'name'  => 'required|max:20'
            ]);

            Gender::create([
                'name'  => $request->name
            ]);

            Alert::success('Success', 'Gender created successfully');
        }catch(Exception $err) {
            $errMessage = $err->getMessage();
            Alert::error('Failed', $errMessage);
        }finally{
            return back();
        }
    }
}
