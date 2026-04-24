<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgruparActasRequest;
use App\Models\Acta;
use App\Http\Requests\StoreActaRequest;
use App\Http\Requests\UpdateActaRequest;
use App\Models\GrupoActa;
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

                return Acta::create($data);
            });

            return sendResponse($acta->load('grupo'));
        } catch (\Throwable $e) {
            saveLog($e,'error');

            return error_response($e);
        }
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

    /**
     * Display the specified resource.
     */
    public function show(Acta $acta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActaRequest $request, Acta $acta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Acta $acta)
    {
        //
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
}
