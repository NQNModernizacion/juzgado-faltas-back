<?php

namespace App\Services;

use App\Models\Acta;
use App\Models\Padron;

class PadronService
{
    /**
     * Procesa y sincroniza los padrones vinculados a un acta.
     *
     * @param array $padrones
     * @param Acta $acta
     * @return void
     */
    public function procesarPadrones(array $padrones, Acta $acta): void
    {
        $padronesIds = [];
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
            $padronesIds[] = [
                'padron_id' => $padron->id,
                'categoria_padron_id' => $padronData['categoria_padron_id'] ?? null
            ];
        }
        
        $acta->syncPadronesConLog($padronesIds);
    }
}
