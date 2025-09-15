<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\NotFoundException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\MethodNotAllowedException;
use App\Exceptions\AuthorizationException as AppAuthorizationException;
use App\Exceptions\ItemNotFoundException;
use App\Http\Middleware\ForceJsonResponse;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: __DIR__ . '/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->api(
            prepend: ForceJsonResponse::class
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        if (request()->is('api/*')) {
            $exceptions->map(NotFoundHttpException::class, NotFoundException::class);
            $exceptions->map(AuthenticationException::class, UnauthorizedException::class);
            $exceptions->map(MethodNotAllowedHttpException::class, MethodNotAllowedException::class);
            $exceptions->map(AuthorizationException::class, AppAuthorizationException::class);
            $exceptions->map(ModelNotFoundException::class, function (ModelNotFoundException $e) {
                return new ItemNotFoundException(
                    "Item não encontrado",
                    $e->getModel(),
                    $e->getIds()
                );
            });
            $exceptions->dontReport([
                AuthenticationException::class,
                UnauthorizedException::class,
            ]);
        }
    })->create();
