<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (! $request->user() || ! $request->user()->hasRole($role)) {
            // Redirect or return response here if the user does not have the required role.
            return response()->json(['error' => 'Unauthorized, only ' . $role . ' is authorized'], 403);
        }
    
        return $next($request);
    }
}
