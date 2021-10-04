<?php

namespace App\Http\Middleware;

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
        if(isset(auth()->user()->player)){
            return $next($request);
        }

        return abort('403');
    }
}
