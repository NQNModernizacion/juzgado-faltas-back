<?php

namespace App\Services;

use App\Models\EstadosGenerales;
use App\Http\Resources\EstadosGeneralesResource;
use App\Http\Resources\InspectorResource;

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
            EstadosGenerales::LABEL_DOCUMENTO_TIPO
        ];

        $estadosAgrupados = EstadosGenerales::porLabels($labelsInteres)->get()->groupBy('label');

        return [
            'sub_tipos'   => EstadosGeneralesResource::collection($estadosAgrupados->get('sub_tipo', [])),
            'leyes'       => EstadosGeneralesResource::collection($estadosAgrupados->get('ley', [])),
            'infractores' => [
                "tipo" => EstadosGeneralesResource::collection($estadosAgrupados->get('DOCUMENTO_TIPO', []))
            ],
            'padrones'    => [
                'tipo_padron' => EstadosGeneralesResource::collection($estadosAgrupados->get('TIPO_PADRON', [])),
                'categorias'  => EstadosGeneralesResource::collection($estadosAgrupados->get('CATEGORIA_PADRON', [])),
            ],
            'inspectores' => InspectorResource::collection($this->inspectorService->getInspectoresHabilitados()),
        ];
    }
}
