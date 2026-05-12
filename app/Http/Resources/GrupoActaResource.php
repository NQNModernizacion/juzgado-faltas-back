<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GrupoActaResource extends JsonResource
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
            'estado' => $this->estado,
            'observacion' => $this->observacion,
            'actas_count' => $this->whenCounted('actas'),
            'actas' => ActaResource::collection($this->whenLoaded('actas')),
        ];
    }
}
