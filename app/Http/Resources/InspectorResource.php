<?php

namespace App\Http\Resources;

use Database\Seeders\EstadosGeneralesSeeder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InspectorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id'     => $this->id,
            'nombre' => $this->nombre, // O el campo que uses para el nombre completo
            'apellido' => $this->apellido,
            'legajo' => $this->legajo,
            'estado' => new EstadosGeneralesResource($this->estadoInspector),
            // 'nombre' => $this->oficina_id, 
        ];
    }
}
