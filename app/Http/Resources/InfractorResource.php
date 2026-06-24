<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InfractorResource extends JsonResource
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
            "id" => $this->id,
            "tipo_id" => $this->tipo_id,
            "documento" => $this->documento,
            "identificacion" => $this->identificacion,
            "nombre" => $this->nombre,
            "domicilio" => $this->domicilio,
            "categoria_infractor_id" => $this->whenPivotLoaded('acta_infractores', function () {
                return $this->pivot->categoria_infractor_id;
            }, $this->categoria_infractor_id ?? null),
            "observaciones" => $this->whenPivotLoaded('acta_infractores', function () {
                return $this->pivot->observaciones;
            }, $this->observaciones ?? null),
        ];
    }
}
