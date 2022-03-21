<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowOnlyAdmin
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
        if (auth()->user()->role->value !== 2) {
            return response()->json([
                "success" => false,
                "errors" => ["unforbidden" => ["You don't have permission"]]
            ], 403);
        }
        return $next($request);
    }
}
