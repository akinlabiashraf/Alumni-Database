<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $role
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], 401);
        }

        if ($role === 'superadmin' && $user->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Super administrator privileges required for this action.',
            ], 403);
        }

        if (!$user->isModerator()) {
            return response()->json([
                'success' => false,
                'message' => 'Access denied. Administrative privileges required.',
            ], 403);
        }

        return $next($request);
    }
}
