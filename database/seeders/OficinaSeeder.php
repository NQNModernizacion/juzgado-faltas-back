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
            ["codigo" => "00", "descripcion" => "Sin oficina", "descripcion_resumida" => "SIN OFICINA", "codigo_caja" => "0720"],
            ["codigo" => "01", "descripcion" => "ADM - Tramite Administrativo", "descripcion_resumida" => "ADMINISTRATIVO", "codigo_caja" => "0720"],
            ["codigo" => "02", "descripcion" => "BRO - Bromatologia", "descripcion_resumida" => "BROMATOLOGIA", "codigo_caja" => "0720"],
            ["codigo" => "03", "descripcion" => "COM - Comercio", "descripcion_resumida" => "COMERCIO", "codigo_caja" => "0719"],
            ["codigo" => "04", "descripcion" => "DEN - Denuncia", "descripcion_resumida" => "DENUNCIA", "codigo_caja" => "0720"],
            ["codigo" => "05", "descripcion" => "DTC - Denuncia Transito Caminera", "descripcion_resumida" => "TRANSITO", "codigo_caja" => "0753"],
            ["codigo" => "06", "descripcion" => "DTM - Denuncia Transito Municipal", "descripcion_resumida" => "TRANSITO", "codigo_caja" => "0718"],
            ["codigo" => "07", "descripcion" => "EsV - Espacios Verdes", "descripcion_resumida" => "ESPACIOS VERDES", "codigo_caja" => "0720"],
            ["codigo" => "08", "descripcion" => "OMU - Obras Municipales", "descripcion_resumida" => "OBRAS", "codigo_caja" => "0720"],
            ["codigo" => "09", "descripcion" => "OPA - Obras Particulares", "descripcion_resumida" => "OBRAS PART.", "codigo_caja" => "0720"],
            ["codigo" => "10", "descripcion" => "OPU - Obras Publicas", "descripcion_resumida" => "OBRAS PUB.", "codigo_caja" => "0720"],
            ["codigo" => "11", "descripcion" => "PrA - Protección Ambiental", "descripcion_resumida" => "AMBIENTE", "codigo_caja" => "0720"],
            ["codigo" => "12", "descripcion" => "TCA - Transito Caminera", "descripcion_resumida" => "TRANSITO", "codigo_caja" => "0753"],
            ["codigo" => "13", "descripcion" => "TMU - Transito Municipal", "descripcion_resumida" => "TRANSITO", "codigo_caja" => "0718"],
            ["codigo" => "14", "descripcion" => "TOJ - Otra Jurisdiccion", "descripcion_resumida" => "OTRA JURISD.", "codigo_caja" => "0720"],
            ["codigo" => "15", "descripcion" => "TTR - Transporte", "descripcion_resumida" => "TRANSPORTE", "codigo_caja" => "0720"],
            ["codigo" => "16", "descripcion" => "ZOO - Zoonosis y Vectores", "descripcion_resumida" => "ZOONOSIS", "codigo_caja" => "0720"],
            ["codigo" => "17", "descripcion" => "LiU - Limpieza Urbana", "descripcion_resumida" => "LIMPIEZA URB.", "codigo_caja" => "0720"],
            ["codigo" => "18", "descripcion" => "RPe - Residuos Peligrosos", "descripcion_resumida" => "RESIDUOS PEL.", "codigo_caja" => "0720"],
            ["codigo" => "19", "descripcion" => "DR - Deportes y Recreación", "descripcion_resumida" => "DEPORTES", "codigo_caja" => "0720"],
            ["codigo" => "20", "descripcion" => "Dir.Relevamiento Externo", "descripcion_resumida" => "RELEV. EXT.", "codigo_caja" => "0720"],
            ["codigo" => "21", "descripcion" => "Buenos Vecinos", "descripcion_resumida" => "BUENOS VECINOS", "codigo_caja" => "0720"],
            ["codigo" => "22", "descripcion" => "Acarreos", "descripcion_resumida" => "ACARREOS", "codigo_caja" => "0720"],
            ["codigo" => "23", "descripcion" => "PRF - Prefectura Naval", "descripcion_resumida" => "PREFECTURA", "codigo_caja" => "0590"],
            ["codigo" => "24", "descripcion" => "TPOC - Multa Transito-Policía Op.C", "descripcion_resumida" => "POLICIA OP. C.", "codigo_caja" => "0591"],
            ["codigo" => "25", "descripcion" => "FM1 - Fotomultas Tribunal de Falt", "descripcion_resumida" => "FOTOMULTA", "codigo_caja" => "1211"],
            ["codigo" => "26", "descripcion" => "FM2 - Fotomultas Tribunal de Falt", "descripcion_resumida" => "FOTOMULTA", "codigo_caja" => "1212"],
            ["codigo" => "27", "descripcion" => "LiUB - LIM.URBANA-BALDÍOS.", "descripcion_resumida" => "LIM. BALDIOS", "codigo_caja" => "0720"],
        ];

        foreach ($oficinas as $oficina) {
            Oficina::updateOrCreate(
                ['codigo' => $oficina['codigo']],
                [
                    'descripcion' => $oficina['descripcion'],
                    'descripcion_resumida' => $oficina['descripcion_resumida'],
                    'codigo_caja' => $oficina['codigo_caja'],
                ]
            );
        }
    }
}
