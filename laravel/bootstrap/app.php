<?php

use App\Http\Middleware\FormatApiResponse;
use App\Http\Middleware\JwtMiddlewareToken;
use Illuminate\Auth\AuthenticationException;
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
            'format.response' => FormatApiResponse::class,
        ]);

        $middleware->appendToGroup('api', [
            FormatApiResponse::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'message' => 'Não autenticado (token inválido ou ausente).'
            ], 401);
        });
    })->create();
