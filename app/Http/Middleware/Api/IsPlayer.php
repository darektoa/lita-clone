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
        if(!$request->has('token'))
            return response()->json(['message' => 'Invalid token field'], 422);

        $token = $request->token;
        $loginToken = LoginToken::where('token', $token);

        if(!$loginToken)
            return response()->json(['message' => 'Unauthorized token'], 422);
        if(!isset($loginToken->user->player))
            return response()->json(['message' => 'You are not player'], 422);

        return $next($request);
    }
}
