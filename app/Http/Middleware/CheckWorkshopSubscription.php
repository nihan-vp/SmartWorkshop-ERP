<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckWorkshopSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // If user is super admin, they don't get locked out
        if ($user && $user->isSuperAdmin()) {
            return $next($request);
        }

        if ($user && $user->workshop) {
            $workshop = $user->workshop;
            
            // Check if subscription is trial and has expired
            if ($workshop->subscription_status === 'trial' && $workshop->trial_ends_at) {
                if (now()->greaterThanOrEqualTo($workshop->trial_ends_at)) {
                    // Redirect to activate license if not already there or logging out
                    if (!$request->routeIs('license.*') && !$request->routeIs('logout')) {
                        return redirect()->route('license.activate');
                    }
                }
            }
        }

        return $next($request);
    }
}
