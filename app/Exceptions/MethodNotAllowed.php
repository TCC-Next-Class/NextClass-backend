<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\ErrorResource;
use Illuminate\Support\Str;

class MethodNotAllowed extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        $routes = app('router')->getRoutes();
        $allowedMethods = [];

        $requestPath = trim($request->path(), '/');

        foreach ($routes as $route) {
            $routeUri = trim($route->uri(), '/');

            if (Str::is($routeUri, $requestPath)) {
                $allowedMethods = array_merge($allowedMethods, $route->methods());
            }
        }

        $allowedMethods = array_values(array_unique($allowedMethods));

        return (new ErrorResource([
            'error' => 'Método não permitido',
            'message' => 'Este método não é permitido.',
            'details' => [
                'allowed_methods' => $allowedMethods
            ]
        ]))->response()->setStatusCode(405)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
