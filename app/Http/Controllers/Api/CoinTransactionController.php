<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CoinTransaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class CoinTransactionController extends Controller
{
    public function store(Request $request) {
        try{
            $validator = Validator::make($request->all(), [
                'coin'  => 'required|numeric|digits_between:0,18'
            ]);

        }catch(Exception $err){
            $errCode    = $err->getCode();
            $errMessage = $err->getMessage();
            response()->json([
                'status'    => $errCode,
                'message'   => $errMessage
            ], $errCode);
        }
    }
}
