<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Si la ruta es el listado, devolvemos solo los campos requeridos para la tabla
        if ($request->routeIs(['actas.index', 'grupo_de_acta', 'grupos_actas.show'])) {
            return [
                'id' => $this->id,
                'numero_acta' => $this->numero_acta,
                'year' => $this->year,
                'juzgado' => $this->juzgado?->descripcion ?? "Sin Juzgado",
                'oficina' => $this->oficina?->descripcion ?? "Sin Oficina",
                'ultimo_movimiento' => $this->latestMovimiento?->oficinaDestino?->descripcion ?? "Sin Movimiento",
                'grupo_acta_id' => $this->grupo_acta_id
            ];
        }

        // Para el detalle, devolvemos todos los datos del acta (igual que registrar_acta)
        return [
            'id' => $this->id,
            'grupo_acta_id' => $this->grupo_acta_id,
            'grupo' => $this->whenLoaded('grupo', function () {
                return new GrupoActaResource($this->grupo);
            }),
            'numero_acta' => $this->numero_acta,
            'year' => $this->year,
            'oficina_id' => $this->oficina_id,
            'fecha_labrada' => $this->fecha_labrada,
            'fecha_carga' => $this->fecha_carga,
            'tipo_id' => $this->tipo_id,
            'sub_tipo_id' => $this->sub_tipo_id,
            'ley_id' => $this->ley_id,
            'lugar' => $this->lugar,
            'calle_id' => $this->calle_id,
            'numero_calle' => $this->numero_calle,
            'cruce_id' => $this->cruce_id,
            'estado_acta_id' => $this->estado_acta_id,
            'fecha_estado' => $this->fecha_estado,
            'desestimada' => $this->desestimada,
            'fecha_notificado' => $this->fecha_notificado,
            'inspector_1_id' => $this->inspector_1_id,
            'inspector_2_id' => $this->inspector_2_id,
            'numero_juzgado_id' => $this->numero_juzgado_id,
            'juez_id' => $this->juez_id,
            'juez' => $this->whenLoaded('juez', function () {
                return new JuezResource($this->juez);
            }),
            'secretaria_id' => $this->secretaria_id,
            'secretaria' => $this->whenLoaded('secretaria', function () {
                return new SecretariaResource($this->secretaria);
            }),
            'causa_id_padre' => $this->causa_id_padre,
            'fecha_vinculacion_padre' => $this->fecha_vinculacion_padre,
            'caratula' => $this->caratula,
            'color' => $this->color,
            'estado_causa_id' => $this->estado_causa_id,
            'fecha_estado_causa' => $this->fecha_estado_causa,
            'fecha_notificado_causa' => $this->fecha_notificado_causa,
            'observacion' => $this->observacion,

            // Relaciones cargadas
            'grupo' => $this->whenLoaded('grupo'),
            'padrones' => $this->whenLoaded('padrones'),
            'infractores' => $this->whenLoaded('infractores'),

            'infracciones' => $this->whenLoaded('infracciones', function () {
                return InfraccionesResource::collection($this->infracciones);
            }),
            'juzgado' => $this->whenLoaded('juzgado'),
            'oficina' => $this->whenLoaded('oficina'),
            'ultimo_movimiento' => $this->whenLoaded('latestMovimiento'),
        ];
    }
}
