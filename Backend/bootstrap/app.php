<?php

use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsAffiliate;
use App\Http\Middleware\IsAgent;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->alias([
            'admin' => IsAdmin::class,
            'agent' => IsAgent::class,
            'affiliate' => IsAffiliate::class,
        ]);

        $middleware->validateCsrfTokens(except: [
          'stripe/*',
          'playfiver/webhook',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
