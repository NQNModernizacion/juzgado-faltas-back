<?php

namespace App\Services;

use App\Models\Acta;
use App\Models\Infractor;

class InfractorService
{
    /**
     * Procesa y sincroniza los infractores vinculados a un acta.
     *
     * @param array $infractores
     * @param Acta $acta
     * @return void
     */
    public function procesarInfractores(array $infractores, Acta $acta): void
    {
        $infractorIds = [];
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
            $infractorIds[] = [
                "infractor_id" => $infractor->id,
                "categoria_infractor_id" => $infractorData['categoria_infractor_id'] ?? null,
                "observaciones" => $infractorData['observaciones'] ?? null
            ];
        }
        
        $acta->syncInfractoresConLog($infractorIds);
    }
}
