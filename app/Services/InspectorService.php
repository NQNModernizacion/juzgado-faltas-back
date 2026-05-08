<?php

namespace App\Services;

use App\Models\Inspector;
use App\Models\EstadosGenerales;

class InspectorService
{
    /**
     * Obtiene todos los inspectores habilitados.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getInspectoresHabilitados()
    {
        $estadoHabilitado = EstadosGenerales::porNombre(EstadosGenerales::HABILITADO)->first();

        return Inspector::where('habilitado_id', $estadoHabilitado->id)
            ->get();
    }
}
