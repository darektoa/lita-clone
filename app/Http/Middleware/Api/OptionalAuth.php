<?php

namespace App\Http\Middleware\Api;

use App\Models\LoginToken;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionalAuth
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
        if(!$request->hasHeader('X-Auth-Token')) return $next($request);

        $token = $request->header('X-Auth-Token');
        $loginToken = LoginToken::where('token', $token)->first();

        if($token === null) return $next($request);
        if(!$loginToken) return response()->json(['message' => 'Unauthorized token'], 401);

        Auth::login($loginToken->user);
        return $next($request);
    }
}
