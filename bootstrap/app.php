<?php

use App\Exceptions\AuthException;
use App\Http\Middleware\APIResponse;
use Illuminate\Foundation\Application;
use App\Http\Middleware\MustReturnJson;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__ . '/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: '/',
        commands: __DIR__.'/../routes/console.php',
        // channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->prepend(MustReturnJson::class);

        $middleware->trustProxies(at: ['*']);

        $middleware->api(prepend: [
            APIResponse::class,
            'throttle:10000,1',
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (AuthenticationException $e) {
            return api_failed_response('Unauthenticated, please login to your account to perform this action', 401);
        });

        $exceptions->dontReport([
            AuthException::class,
        ]);
    })->create();
