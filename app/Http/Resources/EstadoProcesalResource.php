<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EstadoProcesalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'estado' => $this->estado,
            'descripcion' => $this->descripcion,
            'nombre' => $this->descripcion,
            // 'tipo' => $this->tipo,
            // 'es_antec' => (bool)$this->es_antec,
            // 'antec_vig_dias' => $this->antec_vig_dias,
            // 'porc_bonif' => $this->porc_bonif,
            // 'bonif_vig_dias' => $this->bonif_vig_dias,
            // 'gen_notif' => (bool)$this->gen_notif,
            // 'notif_cant_dias' => $this->notif_cant_dias,
            // 'form_autom' => (bool)$this->form_autom,
            // 'perm_pago' => (bool)$this->perm_pago,
            // 'perm_plan' => (bool)$this->perm_plan,
            // 'perm_vol' => (bool)$this->perm_vol,
            // 'desestima' => (bool)$this->desestima,
            // 'tipo_causa' => $this->tipo_causa,
        ];

        // Incluir datos de la tabla pivot si existe relación
        if ($this->pivot) {
            $data['id'] = $this->pivot->id;
            $data['fecha'] = $this->pivot->fecha;
            $data['observacion'] = $this->pivot->observacion;
            $data['infractor_id'] = $this->pivot->infractor_id;
            $data['imputado_datos'] = $this->pivot->imputado_datos;
            $data['user_id'] = $this->pivot->user_id;
            // $data['datos_adicional'] = [
            //     'id' => $this->pivot->id,
            //     'fecha' => $this->pivot->fecha,
            //     'observacion' => $this->pivot->observacion,
            //     'infractor_id' => $this->pivot->infractor_id,
            //     'imputado_datos' => $this->pivot->imputado_datos,
            //     'user_id' => $this->pivot->user_id,
            // ];
        }

        return $data;
    }
}
