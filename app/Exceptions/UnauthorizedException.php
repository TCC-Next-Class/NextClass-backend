<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\ErrorResource;

class UnauthorizedException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        if ($request->is('api/*')) {
            return (new ErrorResource([
                'error' => 'Não autorizado',
                'message' => $this->getMessage() ?: 'Você não está autenticado.'
            ]))->response()->setStatusCode(401);
        }
    }
}
