<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $token = $request->user()->currentAccessToken();

        return [
            'id' => $this->id,
            'name' => $this->name ?? null,
            'abilities' => $this->abilities ?? [],
            'last_used_at' => $this->last_used_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_current' => $token && $this->id === $token->id,
        ];
    }
}
