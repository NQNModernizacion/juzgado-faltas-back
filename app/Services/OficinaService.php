<?php

namespace App\Services;

use App\Models\Oficina;
use App\Models\EstadosGenerales;

class OficinaService
{
    /**
     * Obtiene la lista de oficinas que poseen al menos un inspector habilitado.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOficinasConInspectoresHabilitados()
    {
        $estadoHabilitado = EstadosGenerales::porNombre(EstadosGenerales::HABILITADO)->first();

        return Oficina::with(['inspectores' => function ($query) use ($estadoHabilitado) {
            $query->where('habilitado_id', $estadoHabilitado->id);
        }])
            ->whereHas('inspectores', function ($query) use ($estadoHabilitado) {
                $query->where('habilitado_id', $estadoHabilitado->id);
            })
            ->get();
    }

    /**
     * Carga los inspectores habilitados en una instancia de Oficina.
     *
     * @param Oficina $oficina
     * @return Oficina
     */
    public function cargarInspectoresHabilitados(Oficina $oficina): Oficina
    {
        $estadoHabilitado = EstadosGenerales::porNombre(EstadosGenerales::HABILITADO)->first();

        return $oficina->load(['inspectores' => function ($query) use ($estadoHabilitado) {
            $query->where('habilitado_id', $estadoHabilitado->id);
        }]);
    }
}
