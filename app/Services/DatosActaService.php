<?php

namespace App\Services;

use App\Models\EstadosGenerales;
use App\Models\Infraccion;
use App\Http\Resources\EstadosGeneralesResource;
use App\Http\Resources\InfraccionesResource;
use App\Http\Resources\InspectorResource;
use App\Http\Resources\CalleResource;
use App\Http\Resources\OficinaInternaResource;
use App\Models\Calle;
use App\Models\OficinaInterna;

class DatosActaService
{
    public function __construct(
        protected OficinaService $oficinaService,
        protected InspectorService $inspectorService
    ) {}

    /**
     * Obtiene todos los datos necesarios para el formulario de actas.
     *
     * @return array
     */
    public function getDatosIniciales(): array
    {
        return [
            'oficinas' => $this->oficinaService->getOficinasConInspectoresHabilitados(),
            'combos'   => $this->getCombosGeneral()
        ];
    }

    /**
     * Obtiene los combos generales para el acta.
     *
     * @return array
     */
    public function getCombosGeneral(): array
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
            EstadosGenerales::LABEL_DOCUMENTO_TIPO,
            EstadosGenerales::LABEL_INFRACCION_TIPO
        ];

        $estadosAgrupados = EstadosGenerales::porLabels($labelsInteres)->get()->groupBy('label');

        return [
            'tipos_acta' => EstadosGeneralesResource::collection($estadosAgrupados->get('tipo_acta', [])),
            'sub_tipos' => EstadosGeneralesResource::collection($estadosAgrupados->get('sub_tipo', [])),
            'leyes' => EstadosGeneralesResource::collection($estadosAgrupados->get('ley', [])),
            'infractores' => [
                "tipo" => EstadosGeneralesResource::collection($estadosAgrupados->get('DOCUMENTO_TIPO', []))
            ],
            'padrones' => [
                'tipo_padron' => EstadosGeneralesResource::collection($estadosAgrupados->get('TIPO_PADRON', [])),
                'categorias'  => EstadosGeneralesResource::collection($estadosAgrupados->get('CATEGORIA_PADRON', [])),
            ],
            'inspectores' => InspectorResource::collection($this->inspectorService->getInspectoresHabilitados()),
            'infracciones' => InfraccionesResource::collection(Infraccion::with('tipoInfraccion')->get()),
            'tipos_infracciones' => EstadosGeneralesResource::collection($estadosAgrupados->get('INFRACCION_TIPO', [])),
            'calles' => CalleResource::collection(Calle::all()),
            'oficinas_internas' => OficinaInternaResource::collection(OficinaInterna::all()),
            // 'cruces' => CalleResource::collection(Calle::all()),
        ];
    }
}
