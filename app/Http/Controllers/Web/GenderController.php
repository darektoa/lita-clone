<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\Gender;
use Illuminate\Http\Request;

class GenderController extends Controller
{
    public function index() {
        $genders = Gender::paginate(10);

        return view('pages.admin.setting.genders.index', compact('genders'));
    }
}
