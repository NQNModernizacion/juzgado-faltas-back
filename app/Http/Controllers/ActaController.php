<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgruparActasRequest;
use App\Models\Acta;
use App\Http\Requests\StoreActaRequest;
use App\Http\Requests\UpdateActaRequest;
use App\Models\GrupoActa;
use App\Models\Padron;
use App\Models\Infractor;
use App\Models\Juez;
use App\Models\Juzgado;
use App\Models\OficinaInterna;
use App\Models\Secretaria;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ActaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActaRequest $request)
    {
        try {
            $acta = DB::transaction(function () use ($request) {
                $data = $request->validated();

                $grupoId = $this->resolverGrupoActaId($data['grupo_acta_id'] ?? null);

                $data['grupo_acta_id'] = $grupoId;

                $acta = Acta::create($data);
                $this->procesarPadrones($data['padrones'], $acta);
                $this->procesarInfractores($data['infractores'], $acta);
                $this->procesarInfracciones($data['infracciones'] ?? [], $acta);
                $this->procesarDatosCausa($acta);

                return $acta;
            });

            return sendResponse($acta->load('grupo', 'padrones', 'infractores', 'infracciones'));
        } catch (\Throwable $e) {
            // saveLog($e, 'error');

            return error_response($e);
        }
    }

    private function procesarInfracciones(array $infraccionIds, Acta $acta)
    {
        if (!empty($infraccionIds)) {
            $acta->infracciones()->sync($infraccionIds);
        }
    }

    private function procesarDatosCausa(Acta $acta)
    {
        // "numero_juzgado_id",
        // "oficina_interna_id",
        // "secretaria_id",
        // "juez_id",
        // "causa_id_padre",
        // "fecha_vinculacion_padre",
        // "caratula",
        // "estado_causa_id",
        // "fecha_estado_causa",
        // "fecha_notificado_causa",
        // "observacion",
        $mesPar = (int) Carbon::parse($acta->fecha_hora)->format('m') % 2 == 0;
        $juzgadoQuery = Juzgado::query();
        $juzgado = $mesPar ?  $juzgadoQuery->where('numero_juzgado', 1)->first() : $juzgadoQuery->where('numero_juzgado', 2)->first();
        $oficinaInterna = OficinaInterna::where('codigo', '0')->first()->id;
        $juez = $this->resolverJuezId($juzgado->juez_id);
        $secretaria = $this->resolverSecretariaId($acta, $juez, $juzgado);

        $acta->update([
            'numero_juzgado_id' => $juzgado->id,
            'oficina_interna_id' => $oficinaInterna,
            'secretaria_id' => $secretaria->id,
            'juez_id' => $juez->id,
            'estado_causa_id' => 1,
            'fecha_estado_causa' => Carbon::now()->format('Y-m-d'),
            'fecha_notificado_causa' => $acta->fecha_notificado,
        ]);
    }
    private function resolverJuezId($juezId)
    {
        return Juez::query()->where('id', $juezId)->first();
    }
    private function resolverSecretariaId(Acta $acta, Juez $juez, Juzgado $juzgado)
    {

        $fecha = Carbon::parse($acta->fecha_labrada);
        $dia = $fecha->day;
        $secretariaCodigo = "{$juzgado->numero_juzgado}{$juez->codigo}";
        $secretaria = Secretaria::query();
        if ($dia <= 15) {
            $secretaria->where('codigo', $secretariaCodigo)->where('desde', 1)->where('hasta', 15);
        } else {
            $secretaria->where('codigo', $secretariaCodigo)->where('desde', 16)->where('hasta', 31);
        }
        return $secretaria->first();
    }

    public function agrupar(AgruparActasRequest $request)
    {
        try {
            $grupo = DB::transaction(function () use ($request) {
                return $this->procesarAgrupacion($request->acta_ids);
            });

            return sendResponse($grupo, null, 200);
        } catch (\DomainException $e) {
            // saveLog($e);
            return sendResponse(null, $e->getMessage(), 422);
        } catch (\Throwable $e) {

            saveLog($e);

            return error_response($e);
        }
    }

    private function resolverGrupoActaId(?int $grupoActaId): int
    {
        if ($grupoActaId) {
            $grupo = GrupoActa::with('causa')->find($grupoActaId);

            if (!$grupo) {
                throw new \DomainException('El grupo indicado no existe.');
            }

            if ($grupo->causa) {
                throw new \DomainException('No se puede agregar el acta a un grupo que ya tiene causa.');
            }

            return $grupo->id;
        }

        return GrupoActa::create()->id;
    }

    private function crearActa(array $data): Acta
    {
        $grupo = GrupoActa::create([
            'estado' => 'abierto',
        ]);

        return Acta::create([
            'grupo_acta_id' => $grupo->id,
            'numero' => $data['numero'],
            'fecha_acta' => $data['fecha_acta'] ?? null,
            'estado' => $data['estado'] ?? 'pendiente',
        ]);
    }

    private function procesarAgrupacion(array $actaIds)
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

    private function validarAgrupacion($actas)
    {
        if ($actas->count() < 2) {
            throw new \DomainException('Debés seleccionar al menos 2 actas.');
        }

        // Validamos si todas las actas ya pertenecen al mismo grupo
        $uniqueGrupoIds = $actas->pluck('grupo_acta_id')->unique();
        if ($uniqueGrupoIds->count() === 1 && $uniqueGrupoIds->first() !== null) {
            throw new \DomainException('Las actas seleccionadas ya se encuentran agrupadas en el mismo grupo.');
        }

        foreach ($actas as $acta) {
            if (!$acta->grupo) {
                throw new \DomainException("El acta {$acta->id} no tiene grupo.");
            }

            if ($acta->grupo->causa) {
                throw new \DomainException(
                    "El acta {$acta->id} ya pertenece a un grupo con causa."
                );
            }
        }
    }
    private function crearGrupo()
    {
        return GrupoActa::create([
            'estado' => 'abierto',
        ]);
    }
    private function moverActasAGrupo($actas, $grupo)
    {
        Acta::whereIn('id', $actas->pluck('id'))
            ->update([
                'grupo_acta_id' => $grupo->id,
            ]);
    }
    private function manejarGruposVacios($actas)
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

    private function procesarPadrones(array $padrones, Acta $acta)
    {
        foreach ($padrones as $padronData) {
            $padron = Padron::updateOrCreate(
                [
                    'tipo_id' => $padronData['tipo_id'],
                    'identificacion' => $padronData['identificacion']
                ],
                [
                    'nombre' => $padronData['nombre']
                ]
            );

            $acta->padrones()->attach($padron->id, [
                'categoria_padron_id' => $padronData['categoria_id'] ?? null
            ]);
        }
    }

    private function procesarInfractores(array $infractores, Acta $acta)
    {
        foreach ($infractores as $infractorData) {
            $infractor = Infractor::updateOrCreate(
                [
                    'tipo_id' => $infractorData['tipo_id'],
                    'identificacion' => $infractorData['identificacion']
                ],
                [
                    'documento' => $infractorData['documento'],
                    'nombre' => $infractorData['nombre'],
                    'domicilio' => $infractorData['domicilio'] ?? null
                ]
            );

            $acta->infractores()->attach($infractor->id, [
                'categoria_infractor_id' => $infractorData['categoria_infractor_id'] ?? null,
                'observaciones' => $infractorData['observaciones'] ?? null
            ]);
        }
    }
}
