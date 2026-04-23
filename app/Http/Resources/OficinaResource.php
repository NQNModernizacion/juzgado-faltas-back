<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OficinaResource extends JsonResource
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
            'codigo_caja' => $this->codigo_caja,
            'inspectores' => InspectorResource::collection($this->whenLoaded('inspectores')),
        ];
    }
}
