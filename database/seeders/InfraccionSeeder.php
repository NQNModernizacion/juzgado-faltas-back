<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InfraccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $infracciones = require __DIR__ . '/infracciones_data.php';

        // Truncate to avoid duplicates
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\Infraccion::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Map RV_LOW_VALUE to id for INFRACCION_TIPO
        $tipos = \App\Models\EstadosGenerales::where('label', 'INFRACCION_TIPO')
            ->pluck('id', 'value')
            ->toArray();

        foreach ($infracciones as $data) {
            $tipo_id = $tipos[$data['tipo']] ?? null;

            $parts = explode('-', $data['identificacion']);
            $codigo_sistema = $parts[0] ?? null;
            $articulo = $parts[1] ?? null;
            $inciso = $parts[2] ?? null;

            \App\Models\Infraccion::create([
                'tipo_infraccion_id'     => $tipo_id,
                'identificacion'         => $data['identificacion'],
                'codigo_sistema'         => $codigo_sistema,
                'articulo'               => $articulo,
                'inciso'                 => $inciso,
                'descripcion'            => $data['descripcion'],
                'ley'                    => $data['ley'],
                'grado'                  => $data['grado'],
                'codigo_caja'            => $data['codigo_caja'],
                'valoracion'             => $data['valoracion'],
                'monto'                  => $data['monto'],
                'monto_max'              => $data['monto_max'],
                'monto_minimo'           => $data['monto_minimo'],
                'admite_pago_voluntario' => $data['admite_pago_voluntario'],
            ]);
        }
    }
}
