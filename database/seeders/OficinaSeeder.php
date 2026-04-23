<?php

namespace Database\Seeders;

use App\Models\Oficina;
use Illuminate\Database\Seeder;

class OficinaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $oficinas = [
            ["codigo" => "00", "descripcion" => "Sin oficina", "codigo_caja" => "0720"],
            ["codigo" => "01", "descripcion" => "ADM - Tramite Administrativo", "codigo_caja" => "0720"],
            ["codigo" => "02", "descripcion" => "BRO - Bromatologia", "codigo_caja" => "0720"],
            ["codigo" => "03", "descripcion" => "COM - Comercio", "codigo_caja" => "0719"],
            ["codigo" => "04", "descripcion" => "DEN - Denuncia", "codigo_caja" => "0720"],
            ["codigo" => "05", "descripcion" => "DTC - Denuncia Transito Caminera", "codigo_caja" => "0753"],
            ["codigo" => "06", "descripcion" => "DTM - Denuncia Transito Municipal", "codigo_caja" => "0718"],
            ["codigo" => "07", "descripcion" => "EsV - Espacios Verdes", "codigo_caja" => "0720"],
            ["codigo" => "08", "descripcion" => "OMU - Obras Municipales", "codigo_caja" => "0720"],
            ["codigo" => "09", "descripcion" => "OPA - Obras Particulares", "codigo_caja" => "0720"],
            ["codigo" => "10", "descripcion" => "OPU - Obras Publicas", "codigo_caja" => "0720"],
            ["codigo" => "11", "descripcion" => "PrA - Protección Ambiental", "codigo_caja" => "0720"],
            ["codigo" => "12", "descripcion" => "TCA - Transito Caminera", "codigo_caja" => "0753"],
            ["codigo" => "13", "descripcion" => "TMU - Transito Municipal", "codigo_caja" => "0718"],
            ["codigo" => "14", "descripcion" => "TOJ - Otra Jurisdiccion", "codigo_caja" => "0720"],
            ["codigo" => "15", "descripcion" => "TTR - Transporte", "codigo_caja" => "0720"],
            ["codigo" => "16", "descripcion" => "ZOO - Zoonosis y Vectores", "codigo_caja" => "0720"],
            ["codigo" => "17", "descripcion" => "LiU - Limpieza Urbana", "codigo_caja" => "0720"],
            ["codigo" => "18", "descripcion" => "RPe - Residuos Peligrosos", "codigo_caja" => "0720"],
            ["codigo" => "19", "descripcion" => "DR - Deportes y Recreación", "codigo_caja" => "0720"],
            ["codigo" => "20", "descripcion" => "Dir.Relevamiento Externo", "codigo_caja" => "0720"],
            ["codigo" => "21", "descripcion" => "Buenos Vecinos", "codigo_caja" => "0720"],
            ["codigo" => "22", "descripcion" => "Acarreos", "codigo_caja" => "0720"],
            ["codigo" => "23", "descripcion" => "PRF - Prefectura Naval", "codigo_caja" => "0590"],
            ["codigo" => "24", "descripcion" => "TPOC - Multa Transito-Policía Op.C", "codigo_caja" => "0591"],
            ["codigo" => "25", "descripcion" => "FM1 - Fotomultas Tribunal de Falt", "codigo_caja" => "1211"],
            ["codigo" => "26", "descripcion" => "FM2 - Fotomultas Tribunal de Falt", "codigo_caja" => "1212"],
            ["codigo" => "27", "descripcion" => "LiUB - LIM.URBANA-BALDÍOS.", "codigo_caja" => "0720"],
        ];

        foreach ($oficinas as $oficina) {
            Oficina::updateOrCreate(
                ['codigo' => $oficina['codigo']],
                [
                    'descripcion' => $oficina['descripcion'],
                    'codigo_caja' => $oficina['codigo_caja'],
                ]
            );
        }
    }
}
