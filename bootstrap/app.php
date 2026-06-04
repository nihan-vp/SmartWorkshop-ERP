<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trust all proxies (Vercel, load balancers) so HTTPS is detected correctly
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'super_admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'workshop'    => \App\Http\Middleware\EnsureWorkshopSelected::class,
            'check.trial' => \App\Http\Middleware\CheckWorkshopSubscription::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'CSRF token mismatch',
                    'message' => 'Your page expired. Please refresh and try again.'
                ], 419);
            }
            return redirect()->route('login')->with('error', 'Your session expired. Please sign in again.');
        });
    })->create();

if (env('APP_STORAGE')) {
    $storage = env('APP_STORAGE');
    $app->useStoragePath($storage);
    
    // Ensure all required subdirectories exist to prevent intermittent 500 errors on PaaS
    foreach (['framework/cache', 'framework/sessions', 'framework/views', 'logs'] as $dir) {
        if (!is_dir($storage . '/' . $dir)) {
            @mkdir($storage . '/' . $dir, 0777, true);
        }
    }
}

return $app;
