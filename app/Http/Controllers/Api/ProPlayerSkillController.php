<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProPlayerSkillController extends Controller
{
    public function store() {
        return response()->json(['message', 'Request sent successfully']);
    }
}
