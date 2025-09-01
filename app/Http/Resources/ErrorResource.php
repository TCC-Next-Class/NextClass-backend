<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    public static $wrap = null; // lembrar tirar o wrap somente em erros

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        

        return [
            'error' => $this['error'] ?? 'Erro',
            'message' => $this['message'] ?? 'Um erro inesperado ocorreu',
            'details' => $this['details'] ?? null,
        ];
    }
}
