<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XenditCallback
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $callbackToken = env('XENDIT_MODE') === 'production' ? env('XENDIT_CALLBACK') : env('XENDIT_CALLBACK_DEV');
        
        if($request->header('x-callback-token') === $callbackToken)
            return $next($request);

        return response()->json([
            'status'    => 422,
            'message'   => 'Unprocessable, Invalid callback token'
        ], 422);
    }
}
