<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\ErrorResource;

class NotFoundException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        return (new ErrorResource([
            'error' => 'Recurso não encontrado',
            'message' => "Rota '{$request->path()}' não encontrada.",
        ]))->response()->setStatusCode(404)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
