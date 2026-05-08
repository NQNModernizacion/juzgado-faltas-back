<?php

namespace App\Services;

use App\Models\Acta;
use App\Models\Movimiento;
use Carbon\Carbon;

class MovimientoService
{
    /**
     * Registra el movimiento inicial cuando se crea un acta.
     *
     * @param Acta $acta
     * @return Movimiento
     */
    public function registrarMovimientoInicial(Acta $acta): Movimiento
    {
        $movimiento = new Movimiento;
        $movimiento->acta_id = $acta->id;
        $movimiento->oficina_id_origen = $acta->oficina_interna_id;
        $movimiento->oficina_id_destino = null;
        $movimiento->motivo = 'Creación de Causa';
        $movimiento->fecha_movimiento = Carbon::now()->format('Y-m-d H:i:s');
        $movimiento->save();

        return $movimiento;
    }
}
