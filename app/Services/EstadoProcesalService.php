<?php

namespace App\Services;

use App\Models\EstadoProcesal;
use Illuminate\Support\Facades\DB;

class EstadoProcesalService
{
    /**
     * Obtiene el listado de estados procesales con filtros opcionales.
     */
    public function listarEstados(array $filters = [])
    {
        $query = EstadoProcesal::query();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('estado', 'like', "%{$search}%")
                  ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if (isset($filters['tipo'])) {
            $query->where('tipo', $filters['tipo']);
        }

        return $query->get();
    }

    /**
     * Crea un nuevo estado procesal.
     */
    public function crearEstado(array $data): EstadoProcesal
    {
        return DB::transaction(function () use ($data) {
            return EstadoProcesal::create($data);
        });
    }

    /**
     * Actualiza un estado procesal existente.
     */
    public function actualizarEstado(int $id, array $data): EstadoProcesal
    {
        return DB::transaction(function () use ($id, $data) {
            $estado = EstadoProcesal::findOrFail($id);
            $estado->update($data);
            return $estado;
        });
    }

    /**
     * Elimina (soft delete) un estado procesal.
     */
    public function eliminarEstado(int $id): bool
    {
        return DB::transaction(function () use ($id) {
            $estado = EstadoProcesal::findOrFail($id);
            return $estado->delete();
        });
    }
}
