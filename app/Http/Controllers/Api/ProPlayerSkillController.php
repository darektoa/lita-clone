<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProPlayerSkillController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'game_user_id'  => 'required|min:2|max:20',
            'game_tier'     => ''
        ]);

        return response()->json(['message' => 'Request sent successfully']);
    }
}
