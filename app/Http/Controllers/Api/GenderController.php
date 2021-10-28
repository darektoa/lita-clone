<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gender;
use Illuminate\Http\Request;

class GenderController extends Controller
{
    public function index() {
        $genders = Gender::all();

        return response()->json([
            'status'    => 200,
            'message'   =>'OK',
            'data'      => $genders
        ]);
    }
}
