<?php

namespace Database\Seeders;

use App\Models\Juez;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JuecesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        /* 
        1	Dra. Natalia Dos Santos Almeida
        2	Dra. Romina Doglioli
        12	Romina Doglioli (Juez subrogante)
        21	Dra. Natalia Dos Santos Almeida (Juez subrogante)
        */
        $jueces = [
            ["codigo"=>"1", "nombre" => "Dra. Natalia Dos Santos Almeida"],
            ["codigo"=>"2", "nombre" => "Dra. Romina Doglioli"],
            ["codigo"=>"12", "nombre" => "Romina Doglioli (Juez subrogante)"],
            ["codigo"=>"21", "nombre" => "Dra. Natalia Dos Santos Almeida (Juez subrogante)"],
        ];

        foreach ($jueces as $key => $juez) {
            Juez::updateOrCreate(
                ['codigo' => $juez['codigo']],
                ['nombre' => $juez['nombre']]
            );
        }



    }
}
