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
            $grupoId = $this->grupoService->resolverGrupoActaId($data['grupo_acta_id'] ?? null);

            $data['grupo_acta_id'] = $grupoId;
            $data = array_merge($data, $this->procesarDatosCausa($data));

            $acta = Acta::create($data);

            $this->padronService->procesarPadrones($data['padrones'], $acta);
            $this->infractorService->procesarInfractores($data['infractores'], $acta);
            $this->procesarInfracciones($data['infracciones'] ?? [], $acta);

            $this->movimientoService->registrarMovimientoInicial($acta);

            return $acta;
        });
    }

    /**
     * Procesa las infracciones asociadas al acta.
     *
     * @param array $infraccionIds
     * @param Acta $acta
     * @return void
     */
    public function procesarInfracciones(array $infraccionIds, Acta $acta): void
    {
        if (!empty($infraccionIds)) {
            $acta->syncInfraccionesConLog($infraccionIds);
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
        
        $oficinaInterna = OficinaInterna::where('codigo', '0')->first()->id;
        $juez = $this->resolverJuezId($juzgado->juez_id);
        $secretaria = $this->resolverSecretariaId($data, $juez, $juzgado);

        return [
            'numero_juzgado_id' => $juzgado->id,
            'oficina_interna_id' => $oficinaInterna,
            'secretaria_id' => $secretaria->id,
            'juez_id' => $juez->id,
            'estado_causa_id' => 1,
            'fecha_estado_causa' => Carbon::now()->format('Y-m-d'),
            'fecha_notificado_causa' => $data['fecha_notificado'],
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
     * Orquestador para agrupar actas usando el GrupoService.
     *
     * @param array $actaIds
     * @return \App\Models\GrupoActa
     */
    public function agruparActas(array $actaIds)
    {
        return DB::transaction(function () use ($actaIds) {
            return $this->grupoService->agruparActas($actaIds);
        });
    }
}
