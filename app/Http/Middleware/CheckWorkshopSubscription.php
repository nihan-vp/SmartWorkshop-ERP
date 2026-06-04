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
            
            // Check if subscription is suspended
            if ($workshop->isSuspended()) {
                if (!$request->routeIs('logout')) {
                    auth()->logout();
                    return redirect()->route('login')->with('error', 'Your account has been suspended by the System Administrator.');
                }
            }

            // Check if subscription/trial is expired and configured to restrict features
            if ($workshop->isTrialExpired() && $workshop->restrict_features_on_expiry) {
                // If it's a modifying request (write action), redirect back with a friendly alert
                if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH') || $request->isMethod('DELETE')) {
                    // Do not block activation key requests
                    if (!$request->routeIs('activate_license') && !$request->routeIs('logout')) {
                        return redirect()->back()->with('error', 'Trial period has expired. Writing operations are restricted. Please contact your administrator.');
                    }
                }
            }
        }

        return $next($request);
    }
}
