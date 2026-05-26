<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Validation Exception
        $exceptions->render(function (
            ValidationException $e,
            $request
        ) {
            if ($request->expectsJson()) {

                return response()->json([
                    'success' => false,
                    'messages' => ['Validation failed'],
                    'data' => [],
                    'errors' => $e->errors(),
                    'meta' => [],
                ], 422);
            }
        });

        // Regular Exception
        $exceptions->render(function (
            Throwable $e,
            $request
        ) {

            if ($request->expectsJson()) {

                return response()->json([
                    'success' => false,
                    'message' => [$e->getMessage()],
                    'data' => [],
                    'errors' => app()->environment('local') // check app stage i.e local or production
                        ? [
                            'message' => $e->getMessage(),
                            'file' => $e->getFile(),
                            'line' => $e->getLine(),
                        ]
                        : [],
                    'meta' => [],
                ], 500);
            }
        });
    })->create();
