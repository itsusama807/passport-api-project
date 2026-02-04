<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiPermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        $routeName = $request->route()->getName();

        if (!$routeName) {
            return $next($request);
        }

        $permission = $routeName;

        if (!$user->can($permission)) {
            return response()->json([
                'message' => 'Forbidden',
                'required_permission' => $permission
            ], 403);
        }

        return $next($request);
    }

}

