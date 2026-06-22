<?php

namespace App\Services;

use App\Models\Acta;
use App\Models\Juez;
use App\Models\Juzgado;
use App\Models\OficinaInterna;
use App\Models\Secretaria;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActaService
{
    public function __construct(
        protected MovimientoService $movimientoService,
        protected PadronService $padronService,
        protected InfractorService $infractorService,
        protected GrupoService $grupoService
    ) {}

    /**
     * Registra un acta completa con sus relaciones y movimientos iniciales.
     *
     * @param array $data
     * @return Acta
     */
    public function registrarActa(array $data): Acta
    {
        return DB::transaction(function () use ($data) {
            try {
                // Solo asignamos grupo_acta_id si viene explícitamente en el request.
                // De lo contrario, queda null.
                if (isset($data['grupo_acta_id'])) {
                    $data['grupo_acta_id'] = $this->grupoService->resolverGrupoActaId($data['grupo_acta_id']);
                }

                $data = array_merge($data, $this->procesarDatosCausa($data));

                $acta = Acta::create($data);

                $this->padronService->procesarPadrones($data['padrones'], $acta);
                $this->infractorService->procesarInfractores($data['infractores'], $acta);
                $this->procesarInfracciones($data['infracciones'] ?? [], $acta);

                $this->movimientoService->registrarMovimientoInicial($acta, $data['oficina_destino_id']);

                return $acta;
            } catch (\DomainException $e) {
                throw $e;
            } catch (\Throwable $e) {
                throw $e;
            }
        });
    }

    /**
     * Actualiza un acta existente y sincroniza sus relaciones.
     *
     * @param int $id
     * @param array $data
     * @return Acta
     */
    public function actualizarActa(int $id, array $data): Acta
    {
        return DB::transaction(function () use ($id, $data) {
            try {
                $acta = Acta::findOrFail($id);

                if (isset($data['grupo_acta_id'])) {
                    $data['grupo_acta_id'] = $this->grupoService->resolverGrupoActaId($data['grupo_acta_id']);
                }

                // Asegurar limpieza si el key viene explícitamente en null
                if (array_key_exists('juez_subrogante_id', $data) && is_null($data['juez_subrogante_id'])) {
                    $data['juez_subrogante_id'] = null;
                }
                if (array_key_exists('secretaria_subrogante_id', $data) && is_null($data['secretaria_subrogante_id'])) {
                    $data['secretaria_subrogante_id'] = null;
                }

                $acta->update($data);

                // Reutilizamos los métodos que hacen "sync()", los cuales automáticamente
                // añaden los nuevos, actualizan los modificados y quitan de la relación 
                // a los que ya no están en el array (sin borrarlos de la tabla maestra).
                if (isset($data['padrones'])) {
                    $this->padronService->procesarPadrones($data['padrones'], $acta);
                }
                if (isset($data['infractores'])) {
                    $this->infractorService->procesarInfractores($data['infractores'], $acta);
                }
                if (isset($data['infracciones'])) {
                    $this->procesarInfracciones($data['infracciones'], $acta);
                }

                return $acta;
            } catch (\DomainException $e) {
                throw $e;
            } catch (\Throwable $e) {
                throw $e;
            }
        });
    }

    /**
     * Procesa las infracciones asociadas al acta.
     *
     * @param array $infraccionesData
     * @param Acta $acta
     * @return void
     */
    public function procesarInfracciones(array $infraccionesData, Acta $acta): void
    {
        if (!empty($infraccionesData)) {
            $formatted = [];
            foreach ($infraccionesData as $item) {
                $formatted[$item['infraccion_id']] = [
                    'fecha_infraccion' => isset($item['fecha_infraccion']) ? Carbon::parse($item['fecha_infraccion'])->format('Y-m-d H:i:s') : null,
                    'lugar' => $item['lugar'] ?? null,
                ];
            }
            $acta->syncInfraccionesConLog($formatted);
        }
    }

    /**
     * Calcula y procesa los datos automáticos de la causa (juzgado, secretaría, etc.)
     *
     * @param array $data
     * @return array
     */
    public function procesarDatosCausa(array $data): array
    {
        $mesPar = (int) Carbon::parse($data['fecha_labrada'])->format('m') % 2 == 0;
        $juzgadoQuery = Juzgado::query();
        $juzgado = $mesPar ?  $juzgadoQuery->where('numero_juzgado', 1)->first() : $juzgadoQuery->where('numero_juzgado', 2)->first();

        if (!$juzgado) {
            throw new \DomainException("No se pudo determinar el juzgado de turno para la fecha indicada.");
        }

        $oficinaInternaRecord = OficinaInterna::where('codigo', '0')->first();
        if (!$oficinaInternaRecord) {
            throw new \DomainException("Error de configuración: No se encontró la oficina interna raíz (código 0).");
        }
        $oficinaInterna = $oficinaInternaRecord->id;

        $juez = $this->resolverJuezId($juzgado->juez_id);
        if (!$juez) {
            throw new \DomainException("El juzgado seleccionado no tiene un juez asignado.");
        }

        $secretaria = $this->resolverSecretariaId($data, $juez, $juzgado);
        if (!$secretaria) {
            throw new \DomainException("No se pudo determinar la secretaría de turno para el juzgado {$juzgado->numero_juzgado}.");
        }

        return [
            'numero_juzgado_id' => $juzgado->id,
            'oficina_destino_id' => $oficinaInterna,
            'secretaria_id' => $secretaria->id,
            'juez_id' => $juez->id,
            'estado_causa_id' => 1,
            'fecha_estado_causa' => Carbon::now()->format('Y-m-d'),
            // 'fecha_notificado_causa' => $data['fecha_notificado'],
        ];
    }

    /**
     * Resuelve el Juez basado en el ID.
     *
     * @param int $juezId
     * @return Juez|null
     */
    public function resolverJuezId(int $juezId): ?Juez
    {
        return Juez::query()->where('id', $juezId)->first();
    }

    /**
     * Resuelve la Secretaría basada en la fecha y el juzgado.
     *
     * @param array $data
     * @param Juez $juez
     * @param Juzgado $juzgado
     * @return Secretaria|null
     */
    public function resolverSecretariaId(array $data, Juez $juez, Juzgado $juzgado): ?Secretaria
    {
        $fecha = Carbon::parse($data['fecha_labrada']);
        $dia = $fecha->day;
        $secretaria = Secretaria::query();

        if ($dia <= 15) {
            $secretaria->where('codigo', "{$juzgado->numero_juzgado}1")->where('desde', 1)->where('hasta', 15);
        } else {
            $secretaria->where('codigo', "{$juzgado->numero_juzgado}2")->where('desde', 16)->where('hasta', 31);
        }

        return $secretaria->first();
    }

    /**
     * Obtiene un listado paginado de actas con las relaciones necesarias para la tabla.
     *
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function obtenerListadoActas(array $filters = [], int $perPage = 15)
    {
        $query = Acta::with(['juzgado', 'oficina', 'latestMovimiento.oficinaDestino']);

        // Ejemplo de filtro (preparado para futuro)
        if (!empty($filters['numero_acta'])) {
            $query->where('numero_acta', 'like', "%{$filters['numero_acta']}%");
        }

        if (!empty($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Obtiene el detalle de un acta por su ID con todas sus relaciones.
     *
     * @param int $id
     * @return Acta
     * @throws \DomainException
     */
    public function obtenerDetalleActa(int $id): Acta
    {
        $acta = Acta::with(['grupo', 'padrones', 'infractores', 'infracciones', 'juzgado', 'oficina', 'latestMovimiento.oficinaDestino', 'juez', 'secretaria'])
            ->find($id);

        if (!$acta) {
            throw new \DomainException("El acta con ID {$id} no existe.");
        }

        return $acta;
    }
}
