<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Global middleware
        $middleware->web(append: [
            \App\Http\Middleware\LogRequests::class,
            \App\Http\Middleware\CheckMaintenanceMode::class,
            \App\Http\Middleware\ContentSecurityPolicy::class,
        ]);

        $middleware->api(
            prepend: [
                HandleCors::class,
                EnsureFrontendRequestsAreStateful::class,
            ],
            append: [
                \App\Http\Middleware\LogRequests::class,
            ],
        );

        // Middleware aliases
        $middleware->alias([
            'unit.access' => \App\Http\Middleware\UnitAccessMiddleware::class,
            'log.requests' => \App\Http\Middleware\LogRequests::class,
            'maintenance' => \App\Http\Middleware\CheckMaintenanceMode::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
