<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DailyCoinDataResource;
use App\Models\DailyCoin;
use Illuminate\Http\Request;

class DailyCoinController extends Controller
{
    public function index() {
        $user   = auth()->user();
        $data   = $user->dailyCoin->data;

        return ResponseHelper::make(
            DailyCoinDataResource::collection($data)
        );
    }


    public function store() {
        try{
            $user       = auth()->user();
            $claimed    = DailyCoin::claim($user);

            return ResponseHelper::make($claimed);
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
