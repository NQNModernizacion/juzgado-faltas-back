<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovimientoResource extends JsonResource
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
            'acta_id' => $this->acta_id,
            'motivo' => $this->motivo,
            'fojas' => $this->fojas,
            'fecha_movimiento' => $this->fecha_movimiento,
            'fecha_vecimiento' => $this->fecha_vecimiento,
            'oficina_id_origen' => $this->oficina_id_origen,
            'oficina_origen' => new OficinaResource($this->whenLoaded('oficinaOrigen')),
            'oficina_id_destino' => $this->oficina_id_destino,
            'oficina_destino' => new OficinaResource($this->whenLoaded('oficinaDestino')),
        ];
    }
}
