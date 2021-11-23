<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index() {
        $FAQs = FAQ::all();

        return response()->json([
            'status'    => 200,
            'message'   => 'OK',
            'data'      => $FAQs
        ]);
    }
}
