<?php

use App\Http\Middleware\AuthMachine;
use App\Http\Middleware\ActionCatcher;
use App\Http\Middleware\Authenticated;
use Illuminate\Foundation\Application;
use App\Http\Middleware\UnAuthenticated;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\URL;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'auth' => Authenticated::class,
            'authmac' => AuthMachine::class,
            'unauth' => UnAuthenticated::class,
            'action' => ActionCatcher::class,
        ]);

        // Trust all proxies (Render uses reverse proxy)
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->booted(function () {
        // Force HTTPS in production (Render serves via HTTPS)
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    })
    ->create();

