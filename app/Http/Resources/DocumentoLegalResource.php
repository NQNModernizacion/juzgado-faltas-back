<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentoLegalResource extends JsonResource
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
            'causa_id' => $this->causa_id,
            'plantilla_documento_id' => $this->plantilla_documento_id,
            'tipo' => $this->tipo,
            'file_name' => app(\App\Services\DocumentoLegalService::class)->generarNombreArchivo($this->resource),
            'contenido_html' => $this->contenido_html,
            'metadata' => $this->metadata,
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
            'plantilla' => $this->whenLoaded('plantilla', function () {
                return [
                    'id' => $this->plantilla->id,
                    'codigo' => $this->plantilla->codigo,
                    'nombre' => $this->plantilla->nombre,
                ];
            }),
        ];
    }
}
