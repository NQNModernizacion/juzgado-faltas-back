<?php

namespace Database\Seeders;

use App\Models\Juez;
use App\Models\Juzgado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JuzgadosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /**
         * numero_juzgado
         * descripcion
         * juez_id
         * juez_subrogante_id
         * subrogado
         * 1	1	JUZGADO Nro. 1
         * 2	1	JUZGADO Nro. 2
         */
        $juez1 = Juez::where('codigo', 1)->first();
        $juez2 = Juez::where('codigo', 2)->first();
        $juez12 = Juez::where('codigo', 12)->first();
        $juez21 = Juez::where('codigo', 21)->first();
        $juzgados = [
            ["numero_juzgado" => 1, "descripcion" => "JUZGADO NRO. 1", "juez_id" => $juez1->id, "juez_subrogante_id" => $juez12->id, "subrogado" => false],
            ["numero_juzgado" => 2, "descripcion" => "JUZGADO NRO. 2", "juez_id" => $juez2->id, "juez_subrogante_id" => $juez21->id, "subrogado" => false]

        ];
        foreach ($juzgados as $key => $juezgado) {
            Juzgado::updateOrCreate(
                ['numero_juzgado' => $juezgado['numero_juzgado']],
                ['descripcion' => $juezgado['descripcion'], 'juez_id' => $juezgado['juez_id'], 'juez_subrogante_id' => $juezgado['juez_subrogante_id'], 'subrogado' => $juezgado['subrogado']]
            );
        }
    }
}
