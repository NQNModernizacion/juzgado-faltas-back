<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PadronResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "tipo_id" => $this->tipo_id,
            "identificacion" => $this->identificacion,
            "nombre" => $this->nombre,
            "categoria_padron_id" => $this->whenPivotLoaded('acta_padron', function () {
                return $this->pivot->categoria_padron_id;
            }, $this->categoria_padron_id ?? null),
        ];
    }
}
