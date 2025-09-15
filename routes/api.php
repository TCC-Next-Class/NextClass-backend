<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $routes = collect(app('router')->getRoutes())->filter(function ($route) {
        return str_starts_with($route->uri(), 'api/');
    })->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
        ];
    });
    return response()->json(
        $routes->values(),
        200,
        [],
        JSON_PRETTY_PRINT
    );
});

Route::get('users/search', [UserController::class, 'search']);
Route::apiResource('users', UserController::class);
Route::apiResource('sessions', SessionController::class);
Route::post('sessions/revoke', [SessionController::class, 'revoke']);

Route::get('me', [UserController::class, 'me'])->middleware('auth:sanctum');