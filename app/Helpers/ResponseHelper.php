<?php

namespace App\Helpers;

use Illuminate\Http\Resources\Json\JsonResource;

class ResponseHelper {
    static public function make($data=[], string $message='OK', $status=200) {
        return response()->json([
            'status'  => $status,
            'message' => $message,
            'data'    => $data,
        ], $status);
    }


    static public function error($errors=[], string $message='Failed', $status=500) {
        return response()->json([
            'status'    => $status,
            'message'   => $message,
            'errors'    => $errors,
        ], $status);
    }


    static public function paginate($data=[], string $message='OK', $status=200) {
        $response = collect([
            'status'    => $status,
            'message'   => $message,
        ]);

        if($data instanceof JsonResource) $response = $response->merge([
            'data'          => $data,
            'current_page'  => $data->resource->currentPage(),
            'last_page'     => $data->resource->lastPage(),
            'per_page'      => $data->resource->perPage(),
            'total'         => $data->resource->total(),
        ]);
        else $response = $response ->merge($data);

        return response()->json($response);
    }
}