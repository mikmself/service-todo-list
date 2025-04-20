<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('token');
        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token not provided',
            ], 401);
        }
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token',
            ], 401);
        }
        return $next($request);
    }
}
