<?php

namespace App\Http\Middleware\Api;

use App\Models\LoginToken;
use Closure;
use Illuminate\Http\Request;

class IsAdmin
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
        $loginToken = LoginToken::where('token', $token)->first();
        
        if(!$loginToken)
            return response()->json(['message' => 'Unauthorized token'], 422);
        if(!isset($loginToken->user->admin))
            return response()->json(['message' => 'You are not admin'], 422);

        return $next($request);
    }
}
