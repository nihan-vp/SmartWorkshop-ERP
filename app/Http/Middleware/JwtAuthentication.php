<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\JwtHelper;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthentication
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is not already authenticated via standard session
        if (!Auth::check()) {
            $token = null;

            // 1. Check Authorization header (Bearer token)
            $header = $request->header('Authorization');
            if ($header && preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
                $token = $matches[1];
            }

            // 2. Check cookie if header not present
            if (!$token) {
                $token = $request->cookie('jwt_token');
            }

            // 3. Check query parameter if cookie not present (useful for web sockets or quick testing)
            if (!$token) {
                $token = $request->query('token');
            }

            // 4. Validate and login
            if ($token) {
                $payload = JwtHelper::validateToken($token);
                if ($payload && isset($payload['sub'])) {
                    $user = \App\Models\User::find($payload['sub']);
                    if ($user) {
                        Auth::login($user);
                    }
                }
            }
        }

        return $next($request);
    }
}
