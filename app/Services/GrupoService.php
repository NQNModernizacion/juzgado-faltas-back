<?php

namespace App\Services;

use App\Models\Acta;
use App\Models\GrupoActa;
use DomainException;

class GrupoService
{
    /**
     * Resuelve el ID de un grupo de acta, creándolo si no existe.
     *
     * @param int|null $grupoActaId
     * @return int
     * @throws DomainException
     */
    public function resolverGrupoActaId(?int $grupoActaId): int
    {
        if ($grupoActaId) {
            $grupo = GrupoActa::with('causa')->find($grupoActaId);

            if (!$grupo) {
                throw new DomainException('El grupo indicado no existe.');
            }

            if ($grupo->causa) {
                throw new DomainException('No se puede agregar el acta a un grupo que ya tiene causa.');
            }

            return $grupo->id;
        }

        return GrupoActa::create()->id;
    }

    /**
     * Agrupa múltiples actas en un nuevo grupo.
     *
     * @param array $actaIds
     * @return GrupoActa
     * @throws DomainException
     */
    public function agruparActas(array $actaIds): GrupoActa
    {
        $actas = Acta::with('grupo.causa')
            ->whereIn('id', $actaIds)
            ->lockForUpdate()
            ->get();

        $this->validarAgrupacion($actas);

        $nuevoGrupo = $this->crearGrupo();

        $this->moverActasAGrupo($actas, $nuevoGrupo);

        $this->manejarGruposVacios($actas);

        return $nuevoGrupo->load('actas');
    }

    /**
     * Valida si las actas pueden ser agrupadas.
     *
     * @param \Illuminate\Database\Eloquent\Collection $actas
     * @return void
     * @throws DomainException
     */
    private function validarAgrupacion($actas): void
    {
        if ($actas->count() < 2) {
            throw new DomainException('Debés seleccionar al menos 2 actas.');
        }

        $uniqueGrupoIds = $actas->pluck('grupo_acta_id')->unique();
        if ($uniqueGrupoIds->count() === 1 && $uniqueGrupoIds->first() !== null) {
            throw new DomainException('Las actas seleccionadas ya se encuentran agrupadas en el mismo grupo.');
        }

        foreach ($actas as $acta) {
            if (!$acta->grupo) {
                throw new DomainException("El acta {$acta->id} no tiene grupo.");
            }

            if ($acta->grupo->causa) {
                throw new DomainException(
                    "El acta {$acta->id} ya pertenece a un grupo con causa."
                );
            }
        }
    }

    /**
     * Crea un nuevo grupo de actas abierto.
     *
     * @return GrupoActa
     */
    public function crearGrupo(): GrupoActa
    {
        return GrupoActa::create([
            'estado' => 'abierto',
        ]);
    }

    /**
     * Mueve una colección de actas a un grupo específico.
     *
     * @param \Illuminate\Database\Eloquent\Collection $actas
     * @param GrupoActa $grupo
     * @return void
     */
    private function moverActasAGrupo($actas, GrupoActa $grupo): void
    {
        Acta::whereIn('id', $actas->pluck('id'))
            ->update([
                'grupo_acta_id' => $grupo->id,
            ]);
    }

    /**
     * Maneja el cierre de grupos que quedaron vacíos tras una reagrupación.
     *
     * @param \Illuminate\Database\Eloquent\Collection $actas
     * @return void
     */
    private function manejarGruposVacios($actas): void
    {
        $grupoIds = $actas->pluck('grupo_acta_id')->unique();

        $grupos = GrupoActa::withCount('actas')
            ->whereIn('id', $grupoIds)
            ->get();

        foreach ($grupos as $grupo) {
            if ($grupo->actas_count === 0) {
                $grupo->update(['estado' => 'inactivo']);
                $grupo->delete();
            }
        }
    }
}
