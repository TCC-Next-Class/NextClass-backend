<?php

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

include __DIR__ . '/auth.php';
