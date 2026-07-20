<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PruebaResource extends JsonResource
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
            'tipo_archivo' => $this->tipo_archivo,
            'observacion' => $this->observacion,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'usuario' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
            ],
            'archivo' => $this->when($this->relationLoaded('archivo') && $this->archivo !== null, function () {
                return [
                    'id' => $this->archivo->id,
                    'base_64' => $this->when($this->relationLoaded('archivo') && $this->archivo !== null, function () {
                        return get_file($this->archivo->path_archivo);
                    }),
                    // 'nombre_original' => $this->archivo->nombre_original,
                    // 'extension' => $this->archivo->extension,
                    // 'size' => $this->archivo->size,
                ];
            }),
            // 'archivo_datos' => $this->when($this->relationLoaded('archivo') && $this->archivo !== null, function () {
            //     return get_file($this->archivo->path_archivo);
            // }),
        ];
    }
}
