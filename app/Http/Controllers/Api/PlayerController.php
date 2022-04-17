<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ErrorException;
use App\Models\User;
use App\Helpers\{ResponseHelper, StorageHelper};
use App\Http\Controllers\Controller;
use App\Http\Resources\{ReportResource, UserResource};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlayerController extends Controller
{
    public function show(User $user) {
        return ResponseHelper::make(
            UserResource::make($user->load('player'))
        );
    }

    public function report(Request $request, User $user) {
        try{
            $validator = Validator::make($request->all(), [
                'report' => 'required',
                'proof'  => 'nullable|file'
            ]);

            $reportData = [
                'reporter_id'   => auth()->id(),
                'report'        => $request->report,
                'type'          => 1,
                'status'        => 0,
            ];

            if($validator->fails()) {
                $errors = $validator->errors()->all();
                throw new ErrorException('Unprocessable', 422, $errors);
            }

            if($user->id === auth()->id())
                throw new ErrorException("Not allowed", 403, ["Can't report yourself"]);

            if($request->proof) {
                $proofPath = StorageHelper::put('media/reports', $request->proof);
                $reportData['proof'] = $proofPath;
            }

            $report = $user->reports()->create($reportData);

            return ResponseHelper::make(
                ReportResource::make($report)
            );
        }catch(ErrorException $err) {
            return ResponseHelper::error(
                $err->getErrors(),
                $err->getMessage(),
                $err->getCode(),
            );
        }
    }
}
