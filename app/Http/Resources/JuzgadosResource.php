<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JuzgadosResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'numero_juzgado' => $this->numero_juzgado,
            'descripcion' => $this->descripcion,
            'juez_id' => $this->juez_id,
            'juez' => new JuezResource($this->whenLoaded('juez')),
            'juez_subrogante_id' => $this->juez_subrogante_id,
            'juez_subrogante' => new JuezResource($this->whenLoaded('juezSubrogante')),
        ];
    }
}
