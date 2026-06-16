<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SecretariaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'codigo' => $this->codigo,
            'descripcion' => $this->descripcion,
            'secretaria' => $this->secretaria,
            'desde' => $this->desde,
            'hasta' => $this->hasta
        ];
    }
}
