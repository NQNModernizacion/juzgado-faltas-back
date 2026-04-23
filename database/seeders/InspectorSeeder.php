<?php

namespace Database\Seeders;

use App\Models\EstadosGenerales;
use App\Models\Inspector;
use App\Models\Oficina;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InspectorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        $habilitado = EstadosGenerales::where('label', 'ESTADO_GENERAL')->where('nombre', 'HABILITADO')->first();
        $deshabilitado = EstadosGenerales::where('label', 'ESTADO_GENERAL')->where('nombre', 'DESHABILITADO')->first();

        $oficina_cero = Oficina::where('codigo', '00')->first();
        $oficina_uno = Oficina::where('codigo', '01')->first();
        $oficina_dos = Oficina::where('codigo', '02')->first(); //bromatologia
        $oficina_tres = Oficina::where('codigo', '03')->first(); // comercio
        $oficina_cuatro = Oficina::where('codigo', '04')->first(); 
        $oficina_cinco = Oficina::where('codigo', '05')->first();
        $oficina_seis = Oficina::where('codigo', '06')->first();
        $oficina_siete = Oficina::where('codigo', '07')->first(); // espacios verde
        $oficina_ocho = Oficina::where('codigo', '08')->first();
        $oficina_nueve = Oficina::where('codigo', '09')->first(); // obras particulares 
        $oficina_diez = Oficina::where('codigo', '10')->first(); // obras publicas
        $oficina_once = Oficina::where('codigo', '11')->first(); // proteccion ambiental
        $oficina_doce = Oficina::where('codigo', '12')->first(); // transito caminera
        $oficina_trece = Oficina::where('codigo', '13')->first(); // transito municipal
        $oficina_quince = Oficina::where('codigo', '15')->first(); // transporte
        $oficina_dieciseis = Oficina::where('codigo', '16')->first(); // zoonosis
        $oficina_diecisiete = Oficina::where('codigo', '17')->first(); // limpieza urbana
        $oficina_dieciocho = Oficina::where('codigo', '18')->first(); // residuos peligroso
        $oficina_diecinueve = Oficina::where('codigo', '19')->first(); // deportes y recreacion
        $oficina_veintiuno = Oficina::where('codigo', '21')->first(); // buenos vecinos
        $oficina_veintitres = Oficina::where('codigo', '23')->first(); // prefectura naval
        $oficina_veinticuatro = Oficina::where('codigo', '24')->first(); // multa transito policia op conj
        $oficina_veinticinco = Oficina::where('codigo', '25')->first(); // fotomultas tribunal de faltas 1
        $oficina_veintiseis = Oficina::where('codigo', '26')->first(); // fotomultas tribunal de faltas 2

        $inspectores = [
            ["legajo" => "600", "nombre" => "BROMATOLOGIA", "apellido" => "", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_dos->id],
            ["legajo" => "700", "nombre" => "COMERCIO", "apellido" => "", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_tres->id],
            ["legajo" => "800", "nombre" => "PROTECCION", "apellido" => "AMBIENTAL", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_siete->id],
            ["legajo" => "400", "nombre" => "OBRAS", "apellido" => "PARTICULARES", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_nueve->id],
            ["legajo" => "500", "nombre" => "OBRAS", "apellido" => "PUBLICAS", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_diez->id],
            ["legajo" => "800", "nombre" => "PROTECCION", "apellido" => "AMBIENTAL", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_once->id],
            ["legajo" => "200", "nombre" => "TRANSITO", "apellido" => "PROVINCIAL", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_doce->id],
            ["legajo" => "100", "nombre" => "TRANSITO", "apellido" => "MUNICIPAL", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_trece->id],
            ["legajo" => "300", "nombre" => "TRANSPORTES", "apellido" => "", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_quince->id],
            ["legajo" => "900", "nombre" => "ZOONOSIS", "apellido" => "", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_dieciseis->id],
            ["legajo" => "820", "nombre" => "LIMPIEZA", "apellido" => "URBANA", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_diecisiete->id],
            ["legajo" => "800", "nombre" => "PROTECCION", "apellido" => "AMBIENTAL", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_dieciocho->id],
            ["legajo" => "1100", "nombre" => "FISC.	Y CONTROL GIMNASIOS Y COLONIAS", "apellido" => "", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_diecinueve->id],
            ["legajo" => "100", "nombre" => "TRANSITO", "apellido" => "MUNICIPAL", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_veintiuno->id],
            ["legajo" => "220", "nombre" => "PREFECTURA", "apellido" => "NAVAL", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_veintitres->id],
            ["legajo" => "230", "nombre" => "OPER.CONJ.TRANS.MUN-POLICIA", "apellido" => "", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_veinticuatro->id],
            ["legajo" => "2000", "nombre" => "TRANSITO", "apellido" => "FOTOMULTAS", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_veinticinco->id],
            ["legajo" => "3000", "nombre" => "TRANSITO", "apellido" => "FOTOMULTAS", "habilitado_id" => $habilitado->id, "oficina_id" => $oficina_veintiseis->id],
            
            ["legajo" => "10000", "nombre" => "EXPEDIENTE", "apellido" => "ADMINISTRATIVO", "habilitado_id" => $deshabilitado->id, "oficina_id" => $oficina_veintiseis->id],
            ["legajo" => "1000", "nombre" => "DIRECCIÓN", "apellido" => "GRAL.DE ESPACIOS VERDES", "habilitado_id" => $deshabilitado->id, "oficina_id" => $oficina_veinticinco->id],
            
            ["legajo" => "110", "nombre" => "ESTACIONAMIENTO", "apellido" => "MEDIDO", "habilitado_id" => $deshabilitado->id, "oficina_id" => null],
            ["legajo" => "120", "nombre" => "RELEVAMIENTO", "apellido" => "EXTERNO", "habilitado_id" => $deshabilitado->id, "oficina_id" => null],
            ["legajo" => "130", "nombre" => "DENUNCIAS", "apellido" => "DE TRANSITO", "habilitado_id" => $deshabilitado->id, "oficina_id" => null],
            ["legajo" => "210", "nombre" => "TRÁNSITO", "apellido" => "OTRA JURISDICCIÓN", "habilitado_id" => $deshabilitado->id, "oficina_id" => null],
        ];

        foreach ($inspectores as $inspector) {
            Inspector::updateOrCreate(
                ['legajo' => $inspector['legajo'],'oficina_id' => $inspector['oficina_id']],
                [
                    'nombre' => $inspector['nombre'],
                    'apellido' => $inspector['apellido'],
                    'habilitado_id' => $inspector['habilitado_id'],
                    'oficina_id' => $inspector['oficina_id'],
                ]
            );
        }
    }
}
