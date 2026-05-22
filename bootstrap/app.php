<?php

use App\Http\Middleware\AuthMachine;
use App\Http\Middleware\ActionCatcher;
use App\Http\Middleware\Authenticated;
use Illuminate\Foundation\Application;
use App\Http\Middleware\UnAuthenticated;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

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
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
