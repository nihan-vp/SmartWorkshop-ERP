<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureWorkshopSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isSuperAdmin()) {
                if ($request->session()->has('active_workshop_id')) {
                    return $next($request);
                }
                return redirect()
                    ->route('super_admin.dashboard')
                    ->with('error', 'Workshop pages are for garage admin users only. Log in with the workshop account to manage bills and customers.');
            }

            $workshop = $user->workshop;
            if ($workshop) {
                if ($workshop->isSuspended()) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'error' => 'subscription_inactive',
                            'message' => 'Your workshop account has been suspended. Please contact the administrator.'
                        ], 403);
                    }

                    return response()->view('errors.system_inactive', [
                        'workshop' => $workshop,
                        'isSuspended' => true
                    ], 403);
                }

                if ($workshop->isTrialExpired()) {
                    if ($request->expectsJson()) {
                        return response()->json([
                            'error' => 'trial_expired',
                            'message' => 'Your free trial has expired. Please contact support to upgrade to an active subscription.'
                        ], 403);
                    }

                    return response()->view('errors.system_inactive', [
                        'workshop' => $workshop,
                        'isSuspended' => false
                    ], 403);
                }
            }
        }

        return $next($request);
    }
}
