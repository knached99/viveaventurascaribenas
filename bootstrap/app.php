<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TrackVisitors;
// use App\Http\Middleware\VisitorMiddleware; // Import your middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withCommands([
        __DIR__.'/../app/Domain/Orders/Commands',
    ]
        
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add your VisitorMiddleware here
        $middleware->append(TrackVisitors::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
