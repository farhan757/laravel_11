<?php

use App\Http\Middleware\AuthSSOcustomMiddleware;
use App\Http\Middleware\CheckUniqueCookie;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'authssocustom' => AuthSSOcustomMiddleware::class,
            'check.unique.cookie' => CheckUniqueCookie::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
