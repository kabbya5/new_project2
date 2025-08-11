<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try {
            $user = Auth::guard('sanctum')->user();

            if($user && $user->role != 'admin'){
                return response()->json(['error' => 'This action only for admin'], 401);
            }

            if (!$user) {
                return response()->json(['error' => 'Token is invalid or expired'], 401);
            }
        }catch (\Exception $e) {
            return response()->json(['error' => 'Token is invalid or expired'], 401);
        }

        return $next($request);
    }
}
