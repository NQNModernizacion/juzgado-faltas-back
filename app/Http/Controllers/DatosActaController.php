<?php

namespace App\Http\Controllers;

use App\Http\Resources\EstadosGeneralesResource;
use App\Http\Resources\OficinaResource;
use App\Models\EstadosGenerales;
use App\Models\Oficina;
use Illuminate\Http\Request;

class DatosActaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $oficinas = $this->getOficinasConInspectoresHabilitados();

            return sendResponse([
                'oficinas' => OficinaResource::collection($oficinas),
                'combos'   => $this->getCombosGeneral()
            ]);
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    public function getDatosPorOficina(Oficina $oficina)
    {
        try {
            $estadoHabilitado = EstadosGenerales::porNombre(EstadosGenerales::HABILITADO)->first();

            // Utilizamos 'load' para cargar la relación filtrada en la instancia ya existente
            $oficina->load(['inspectores' => function ($query) use ($estadoHabilitado) {
                $query->where('habilitado_id', $estadoHabilitado->id);
            }]);

            return sendResponse([
                'oficina' => new OficinaResource($oficina),
                'combos'  => $this->getCombosGeneral()
            ]);
        } catch (\Throwable $th) {
            return error_response($th, __FUNCTION__);
        }
    }

    /**
     * Obtiene la lista de oficinas que poseen al menos un inspector habilitado.
     */
    private function getOficinasConInspectoresHabilitados()
    {
        $estadoHabilitado = EstadosGenerales::porNombre(EstadosGenerales::HABILITADO)->first();

        return Oficina::with(['inspectores' => function ($query) use ($estadoHabilitado) {
            $query->where('habilitado_id', $estadoHabilitado->id);
        }])
        ->whereHas('inspectores', function ($query) use ($estadoHabilitado) {
            $query->where('habilitado_id', $estadoHabilitado->id);
        })
        ->get();
    }

    /**
     * Agrupa los estados generales necesarios para los select/combos del frontend.
     */
    private function getCombosGeneral()
    {
        $labelsInteres = [
            EstadosGenerales::LABEL_TIPO_ACTA,
            EstadosGenerales::LABEL_SUB_TIPO,
            EstadosGenerales::LABEL_LEY,
            EstadosGenerales::LABEL_ESTADO,
            EstadosGenerales::LABEL_INFRACCION_GRADO,
            EstadosGenerales::LABEL_CATEGORIA_PADRON,
            EstadosGenerales::LABEL_TIPO_PADRON,
            EstadosGenerales::LABEL_CATEGORIA_INFRACTOR,
            EstadosGenerales::LABEL_DOCUMENTO_TIPO
        ];

        $estadosAgrupados = EstadosGenerales::porLabels($labelsInteres)->get()->groupBy('label');

        return [
            'tipos_acta'  => EstadosGeneralesResource::collection($estadosAgrupados->get('tipo_acta', [])),
            'sub_tipos'   => EstadosGeneralesResource::collection($estadosAgrupados->get('sub_tipo', [])),
            'leyes'       => EstadosGeneralesResource::collection($estadosAgrupados->get('ley', [])),
            'infractores' => [
                "tipo" => EstadosGeneralesResource::collection($estadosAgrupados->get('DOCUMENTO_TIPO', []))
            ],
            'padrones'    => [
                'tipo_padron' => EstadosGeneralesResource::collection($estadosAgrupados->get('TIPO_PADRON', [])),
                'categorias'  => EstadosGeneralesResource::collection($estadosAgrupados->get('CATEGORIA_PADRON', [])),
            ]
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
