<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->input('email').$request->ip());
        });

        // Force HTTPS in production (Netlify, Render, etc.)
        // Trust all proxies so X-Forwarded-Proto is respected behind Netlify's CDN/load balancer
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', 'on');
        }
    }
}
