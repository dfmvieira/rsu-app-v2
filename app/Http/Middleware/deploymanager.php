<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class deploymanager
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
        if(empty($user) || !$user->hasRole('deploymanager')){
            return response()->json(['message' => 'Unauthenticated. Deploy Manager role required'], 401);
        }
        return $next($request);
    }
}
