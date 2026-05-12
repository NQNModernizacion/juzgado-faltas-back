<?php
 
namespace App\Http\Resources;
 
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
 
class InfraccionesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'identificacion' => $this->identificacion,
            'tipo_infraccion' => [
                'id'     => $this->tipoInfraccion?->id,
                'nombre' => $this->tipoInfraccion?->nombre,
                'value'  => $this->tipoInfraccion?->value,
            ],
        ];
    }
}
