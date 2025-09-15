<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use App\Http\Resources\ErrorResource;
use Throwable;

class ItemNotFoundException extends Exception
{
    public string $model;
    public array $ids;

    public function __construct(string $message, string $model, array $ids, ?Throwable $previous = null)
    {
        parent::__construct($message, 0, $previous);
        $this->model = $model;
        $this->ids   = $ids;
    }
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request)
    {
        return (new ErrorResource([
            'error' => 'Item não encontrado',
            'message' => "Item '{$request->path()}' não encontrado.",
            'details' => [
                'model' => $this->model,
                'id' => $this->ids[0] ?? null,
            ]
        ]))->response()->setStatusCode(404)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
