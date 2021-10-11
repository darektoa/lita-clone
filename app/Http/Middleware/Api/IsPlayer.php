<?php

namespace App\Http\Middleware\Api;

use App\Models\LoginToken;
use Closure;
use Illuminate\Http\Request;

class IsPlayer
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
        if(!$request->hasHeader('X-Auth-Token'))
            return response()->json(['message' => 'Invalid X-Auth-Token header'], 422);

        $token = $request->header('X-Auth-Token');
        $loginToken = LoginToken::where('token', $token);

        if(!$loginToken)
            return response()->json(['message' => 'Unauthorized token'], 422);
        if(!isset($loginToken->user->player))
            return response()->json(['message' => 'Forbidden to access'], 403);

        return $next($request);
    }
}
