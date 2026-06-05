<?php

namespace App\Services;

use App\Models\Acta;
use App\Models\GrupoActa;
use DomainException;
use Illuminate\Support\Facades\DB;

class GrupoService
{
    /**
     * Resuelve el ID de un grupo de acta, creándolo si no existe.
     *
     * @param int|null $grupoActaId
     * @return int
     * @throws DomainException
     */
    public function resolverGrupoActaId(?int $grupoActaId): ?int
    {
        if (!$grupoActaId) {
            return null;
        }

        $grupo = GrupoActa::find($grupoActaId);

        if (!$grupo) {
            throw new DomainException('El grupo indicado no existe.');
        }

        return $grupo->id;
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
        return DB::transaction(function () use ($actaIds) {
            $actas = Acta::whereIn('id', $actaIds)
                ->lockForUpdate()
                ->get();

            $this->validarAgrupacion($actas);

            $nuevoGrupo = $this->crearGrupo();

            $this->moverActasAGrupo($actas, $nuevoGrupo);

            return $nuevoGrupo->load('actas');
        });
    }

    /**
     * Añade actas huérfanas a un grupo existente.
     *
     * @param int $grupoId
     * @param array $actaIds
     * @return GrupoActa
     * @throws DomainException
     */
    public function añadirAGrupo(int $grupoId, array $actaIds): GrupoActa
    {
        return DB::transaction(function () use ($grupoId, $actaIds) {
            $grupo = GrupoActa::find($grupoId);
            if (!$grupo) {
                throw new DomainException('El grupo indicado no existe.');
            }

            $actas = Acta::whereIn('id', $actaIds)
                ->lockForUpdate()
                ->get();

            foreach ($actas as $acta) {
                if ($acta->grupo_acta_id !== null) {
                    if ($acta->grupo_acta_id === $grupo->id) {
                        throw new DomainException("El acta {$acta->id} ya se encuentra en el grupo seleccionado.");
                    }
                    throw new DomainException("El acta {$acta->id} ya pertenece a otro grupo.");
                }
            }

            $this->moverActasAGrupo($actas, $grupo);

            return $grupo->load('actas');
        });
    }

    /**
     * Quita las actas de sus grupos y limpia grupos vacíos o con una sola acta.
     *
     * @param array $actaIds
     * @return void
     */
    public function desagruparActas(array $actaIds): void
    {
        DB::transaction(function () use ($actaIds) {
            $actas = Acta::whereIn('id', $actaIds)
                ->lockForUpdate()
                ->get();

            $grupoIds = $actas->pluck('grupo_acta_id')->filter()->unique();

            if ($grupoIds->count() > 1) {
                throw new DomainException('No se pueden desagrupar actas que pertenecen a grupos distintos.');
            }

            if ($grupoIds->count() === 0) {
                throw new DomainException('Las actas seleccionadas no pertenecen a ningún grupo.');
            }

            // Si se pasaron varias actas y algunas no tenían grupo (pero solo hay un grupo ID único),
            // validamos que TODAS las seleccionadas pertenecieran a ese grupo.
            if ($actas->count() !== $actas->where('grupo_acta_id', $grupoIds->first())->count()) {
                throw new DomainException('Todas las actas seleccionadas deben pertenecer al mismo grupo.');
            }

            // Desagrupamos
            Acta::whereIn('id', $actas->pluck('id'))
                ->update(['grupo_acta_id' => null]);

            // Limpiamos grupos afectados
            $this->limpiarGruposInvalidos($grupoIds);
        });
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

        // Para agrupar (nuevo), TODAS deben ser huérfanas
        foreach ($actas as $acta) {
            if ($acta->grupo_acta_id !== null) {
                throw new DomainException("El acta {$acta->id} ya pertenece a un grupo. Usá 'Añadir a Grupo' en su lugar.");
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
     * Limpia grupos que quedaron vacíos o con una sola acta.
     *
     * @param \Illuminate\Support\Collection $grupoIds
     * @return void
     */
    private function limpiarGruposInvalidos($grupoIds): void
    {
        $grupos = GrupoActa::withCount('actas')
            ->whereIn('id', $grupoIds)
            ->get();

        foreach ($grupos as $grupo) {
            if ($grupo->actas_count < 2) {
                // Si queda una sola acta, la dejamos huérfana
                if ($grupo->actas_count === 1) {
                    Acta::where('grupo_acta_id', $grupo->id)
                        ->update(['grupo_acta_id' => null]);
                }

                $grupo->update(['estado' => 'inactivo']);
                $grupo->delete();
            }
        }
    }
    /**
     * Obtiene un listado paginado de grupos de actas.
     *
     * @param array $filters
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function listarGrupos(array $filters = [], int $perPage = 15)
    {
        $query = GrupoActa::withCount('actas');

        if (!empty($filters['estado'])) {
            $query->where('estado', $filters['estado']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Obtiene el detalle de un grupo con sus actas y relaciones.
     *
     * @param int $id
     * @return GrupoActa
     * @throws DomainException
     */
    public function obtenerDetalleGrupo(int $id): GrupoActa
    {
        $grupo = GrupoActa::with([
            'actas.padrones',
            'actas.infractores',
            'actas.infracciones',
            'actas.juzgado',
            'actas.oficina',
            'actas.latestMovimiento.oficinaDestino'
        ])->find($id);

        if (!$grupo) {
            throw new DomainException("El grupo con ID {$id} no existe.");
        }

        return $grupo;
    }

    /**
     * Obtiene el detalle de un grupo con sus actas y relaciones.
     *
     * @param int $id
     * @return GrupoActa
     * @throws DomainException
     */
    public function obtenerGrupoByActa(int $acta_id): GrupoActa
    {
        $acta = Acta::find($acta_id);
        if (!$acta) {
            throw new DomainException("El acta con ID {$acta_id} no existe.");
        }

        $grupo = GrupoActa::with([
            'actas' => function ($query) use ($acta_id) {
                $query->where('id', '!=', $acta_id)->with([
                    'padrones',
                    'infractores',
                    'infracciones',
                    'juzgado',
                    'oficina',
                    'latestMovimiento.oficinaDestino'
                ]);
            }
        ])->find($acta->grupo_acta_id);

        if (!$grupo) {
            throw new DomainException("El grupo con ID {$acta->grupo_acta_id} no existe.");
        }

        return $grupo;
    }
}
