<?php

namespace Database\Seeders;

use App\Models\OficinaInterna;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OficinasInternasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * codigo
         * descripcion
         * 0	Sin Ubicacion
         * 10	Oficina Notificación
         * 15	Mesa De Entrada
         * 20	Prosecretaría
         * 21	Prosecretaría 1
         * 22	Prosecretaría 2
         * 30	Secretaría 1
         * 40	Secretaría 2
         * 45	Despacho
         * 50	Juez
         * 55	Administración
         * 60	Atenc.Al Público
         * 65	Informática
         * 70	Pase C/Expediente
         * 75	Archivo
         * 80	Sector Caja
         * 85	Sector Legales
         * 90	Relaciones Institucionales
         * 91	Juzgado 1
         * 92	Juzgado 2
         */

        $oficinasInternas=[
            ["codigo" => 0, "descripcion" => "Sin Ubicacion"],
            ["codigo" => 10, "descripcion" => "Oficina Notificación"],
            ["codigo" => 15, "descripcion" => "Mesa De Entrada"],
            ["codigo" => 20, "descripcion" => "Prosecretaría"],
            ["codigo" => 21, "descripcion" => "Prosecretaría 1"],
            ["codigo" => 22, "descripcion" => "Prosecretaría 2"],
            ["codigo" => 30, "descripcion" => "Secretaría 1"],
            ["codigo" => 40, "descripcion" => "Secretaría 2"],
            ["codigo" => 45, "descripcion" => "Despacho"],
            ["codigo" => 50, "descripcion" => "Juez"],
            ["codigo" => 55, "descripcion" => "Administración"],
            ["codigo" => 60, "descripcion" => "Atenc.Al Público"],
            ["codigo" => 65, "descripcion" => "Informática"],
            ["codigo" => 70, "descripcion" => "Pase C/Expediente"],
            ["codigo" => 75, "descripcion" => "Archivo"],
            ["codigo" => 80, "descripcion" => "Sector Caja"],
            ["codigo" => 85, "descripcion" => "Sector Legales"],
            ["codigo" => 90, "descripcion" => "Relaciones Institucionales"],
            ["codigo" => 91, "descripcion" => "Juzgado 1"],
            ["codigo" => 92, "descripcion" => "Juzgado 2"],
        ];

        foreach ($oficinasInternas as $key => $oficinaInterna) {
            OficinaInterna::updateOrCreate(
                ['codigo' => $oficinaInterna['codigo']],
                ['descripcion' => $oficinaInterna['descripcion']]
            );
        }
    }
}
