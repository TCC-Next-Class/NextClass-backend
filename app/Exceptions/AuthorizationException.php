<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\ErrorResource;

class AuthorizationException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        return (new ErrorResource([
            'error' => 'Não autorizado',
            'message' => $this->getMessage() ?: 'Você não tem permissão para acessar este recurso.'
        ]))->response()->setStatusCode(401)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
