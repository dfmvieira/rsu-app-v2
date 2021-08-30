<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Planner
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
        $user = auth()->user();
        if(empty($user) || !$user->hasRole('planner')){
            return response()->json(['message' => 'Unauthenticated. Planner role required'], 401);
        }
        return $next($request);
    }
}
