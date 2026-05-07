<?php

namespace Database\Seeders;

use App\Models\Secretaria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SecretariaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        codigo	descripcion	secretaria	fecha_desde	fecha_hasta
            11	Dra. Belén Soledad Campos	Secretaria	1	15
            111	Dra.María Soledad Diez	Secretaria Subrogante		
            12	Dra.María Soledad Diez	Secretaria	16	31
            121		Secretaria Subrogante		
            21	Dr. Manuel Andrés Gonzalez	Secretaria	1	15
            211	Dra. María Soledad González Landa	Secretaria Subrogante		
            22	Dra. María Soledad González Landa	Secretaria	16	31
            221	Dr. Manuel Andrés Gonzalez	Secretaria Subrogante
        */

        $secretarias = [
            ["codigo" => "11", "descripcion" => "Dra. Belén Soledad Campos", "secretaria" => "Secretaria", "desde" => 1, "hasta" => 15],
            ["codigo" => "111", "descripcion" => "Dra.María Soledad Diez", "secretaria" => "Secretaria Subrogante", "desde" => null, "hasta" => null],
            ["codigo" => "12", "descripcion" => "Dra.María Soledad Diez", "secretaria" => "Secretaria", "desde" => 16, "hasta" => 31],
            ["codigo" => "121", "descripcion" => null, "secretaria" => "Secretaria Subrogante", "desde" => null, "hasta" => null],
            ["codigo" => "21", "descripcion" => "Dr. Manuel Andrés Gonzalez", "secretaria" => "Secretaria", "desde" => 1, "hasta" => 15],
            ["codigo" => "211", "descripcion" => "Dra. María Soledad González Landa", "secretaria" => "Secretaria Subrogante", "desde" => null, "hasta" => null],
            ["codigo" => "22", "descripcion" => "Dra. María Soledad González Landa", "secretaria" => "Secretaria", "desde" => 16, "hasta" => 31],
            ["codigo" => "221", "descripcion" => "Dr. Manuel Andrés Gonzalez", "secretaria" => "Secretaria Subrogante", "desde" => null, "hasta" => null],
        ];
        foreach ($secretarias as $key => $secretaria) {
            Secretaria::updateOrCreate(
                ['codigo' => $secretaria['codigo']],
                ['descripcion' => $secretaria['descripcion'], 'secretaria' => $secretaria['secretaria'], 'desde' => $secretaria['desde'], 'hasta' => $secretaria['hasta']]
            );
        }
    }
}
