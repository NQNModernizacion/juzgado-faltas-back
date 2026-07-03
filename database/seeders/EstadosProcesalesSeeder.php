<?php

namespace Database\Seeders;

use App\Models\EstadoProcesal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosProcesalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = file_get_contents(database_path('seeders/estadosProcesales.json'));
        $estados = json_decode($json, true);

        foreach ($estados as $estado) {
            EstadoProcesal::updateOrCreate(
                ['estado' => $estado['estado']],
                [
                    'descripcion' => $estado['descripcion'],
                    'tipo' => $estado['tipo'],
                    'es_antec' => $estado['es_antec'] ?? false,
                    'antec_vig_dias' => $estado['antec_vig_dias'],
                    'porc_bonif' => $estado['porc_bonif'],
                    'bonif_vig_dias' => $estado['bonif_vig_dias'],
                    'gen_notif' => $estado['gen_notif'] ?? false,
                    'notif_cant_dias' => $estado['notif_cant_dias'],
                    'form_autom' => $estado['form_autom'],
                    'perm_pago' => $estado['perm_pago'] ?? true,
                    'perm_plan' => $estado['perm_plan'] ?? true,
                    'perm_vol' => $estado['perm_vol'] ?? true,
                    'desestima' => $estado['desestima'] ?? false,
                    'tipo_causa' => $estado['tipo_causa'],
                ]
            );
        }
    }
}
