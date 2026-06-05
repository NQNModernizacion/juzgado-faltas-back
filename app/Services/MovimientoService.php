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
     * @param int $oficinaDestinoId
     * @return Movimiento
     */
    public function registrarMovimientoInicial(Acta $acta, int $oficinaDestinoId): Movimiento
    {
        $movimiento = new Movimiento;
        $movimiento->acta_id = $acta->id;
        $movimiento->oficina_id_origen = null;
        $movimiento->oficina_id_destino = $oficinaDestinoId;
        $movimiento->motivo = 'Creación de Causa';
        $movimiento->fecha_movimiento = Carbon::now();
        $movimiento->save();

        return $movimiento;
    }

    /**
     * Mueve una causa de una oficina a otra.
     *
     * @param array $data
     * @return Movimiento
     */
    public function moverCausa(array $data): Movimiento
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data) {
            try {
                // Buscamos el último movimiento para obtener la oficina actual
                $ultimoMovimiento = Movimiento::where('acta_id', $data['acta_id'])
                    ->latest()
                    ->first();

                $movimiento = new Movimiento;
                $movimiento->acta_id = $data['acta_id'];
                $movimiento->oficina_id_origen = $ultimoMovimiento ? $ultimoMovimiento->oficina_id_destino : null;
                $movimiento->oficina_id_destino = $data['oficina_id_destino'];
                $movimiento->motivo = $data['motivo'] ?? null;
                $movimiento->fojas = $data['fojas'] ?? null;
                $movimiento->fecha_movimiento = Carbon::now();
                $movimiento->save();

                return $movimiento;
            } catch (\Throwable $e) {
                // El rollback ocurre automáticamente al fallar el closure de DB::transaction,
                // pero capturamos para loguear o lanzar una excepción personalizada si fuera necesario.
                throw $e;
            }
        });
    }

    /**
     * Obtiene todos los movimientos de un acta ordenados del más reciente al más antiguo.
     *
     * @param int $actaId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerPorActa(int $actaId)
    {
        return Movimiento::where('acta_id', $actaId)
            ->with(['oficinaOrigen', 'oficinaDestino'])
            ->orderBy('fecha_movimiento', 'desc')
            ->orderBy('id', 'desc')
            ->get();
    }
}
