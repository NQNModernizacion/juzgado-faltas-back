<?php

namespace Database\Seeders;

use App\Models\EstadosGenerales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadosGeneralesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $estados = [


            /* tipo_acta */
            ["label" => "tipo_acta", "nombre" => "Bromatología / Comercio", "value" => "BC",    "descripcion" => "Bromatología / Comercio"],
            ["label" => "tipo_acta", "nombre" => "Espacios Verdes", "value" => "EV",    "descripcion" => "Espacios Verdes"],
            ["label" => "tipo_acta", "nombre" => "Limpieza Urbana", "value" => "LU",    "descripcion" => "Limpieza Urbana"],
            ["label" => "tipo_acta", "nombre" => "Otros", "value" => "OT",    "descripcion" => "Otros"],
            ["label" => "tipo_acta", "nombre" => "Protección Ambiental", "value" => "PA",    "descripcion" => "Protección Ambiental"],
            ["label" => "tipo_acta", "nombre" => "Retributivo / Obras", "value" => "RO",    "descripcion" => "Retributivo / Obras"],
            ["label" => "tipo_acta", "nombre" => "Residuos Peligrosos", "value" => "RP",    "descripcion" => "Residuos Peligrosos"],
            ["label" => "tipo_acta", "nombre" => "Transporte", "value" => "TP",    "descripcion" => "Transporte"],
            ["label" => "tipo_acta", "nombre" => "Tránsito", "value" => "TR",    "descripcion" => "Tránsito"],
            ["label" => "tipo_acta", "nombre" => "Zoonosis y Vectores", "value" => "ZV",    "descripcion" => "Zoonosis y Vectores"],
            /* sub tipo */
            ["label" => "sub_tipo", "nombre" => "Centro Carga Actas", "value" => "CCA",    "descripcion" => "Centro Carga Actas"],
            ["label" => "sub_tipo", "nombre" => "Tribunal de faltas", "value" => "Tribunal ",    "descripcion" => "Tribunal de faltas"],
            /* Ley */
            ["label" => "ley", "nombre" => null, "value" => 0,    "descripcion" => null],
            ["label" => "ley", "nombre" => "Ord 8833", "value" => 1,    "descripcion" => "Ord 8833"],
            ["label" => "ley", "nombre" => "Ley 12028", "value" => 2,    "descripcion" => "Ley 12028"],

            /* Estados */
            ["label" => "estado", "nombre" => "Baja", "value" => "BA",    "descripcion" => "Baja"],
            ["label" => "estado", "nombre" => "Género Causa", "value" => "C",    "descripcion" => "Género Causa"],
            ["label" => "estado", "nombre" => "Notificado", "value" => "NOT",    "descripcion" => "Notificado"],
            ["label" => "estado", "nombre" => "Sin Datos", "value" => "SD",    "descripcion" => "Sin Datos"],

            /*Grado Infraccion */
            ["label" => "INFRACCION_GRADO", "nombre" => "0 - Grado 0", "value" => "0",    "descripcion" => "0 - Grado 0"],
            ["label" => "INFRACCION_GRADO", "nombre" => "1 - Contrav. de Transito", "value" => "1",    "descripcion" => "1 - Contrav. de Transito"],
            ["label" => "INFRACCION_GRADO", "nombre" => "2 - Atenta contra la Seg. del Transito", "value" => "2",    "descripcion" => "2 - Atenta contra la Seg. del Transito"],
            ["label" => "INFRACCION_GRADO", "nombre" => "3 - Atenta contra la Seg. de las Personas", "value" => "3",    "descripcion" => "3 - Atenta contra la Seg. de las Personas"],
            ["label" => "INFRACCION_GRADO", "nombre" => "4 - Atenta contra la Seg. Publica", "value" => "4",    "descripcion" => "4 - Atenta contra la Seg. Publica"],

            /* Motivo acta de desestimada */
            ["label" => "ACTA_DESEST_MOT", "nombre" => "Marca del Vehiculo Incorrecta", "value" => "01",    "descripcion" => "Marca del Vehiculo Incorrecta"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "Falta marca del Vehiculo", "value" => "02",    "descripcion" => "Falta marca del Vehiculo"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "Fecha de Acta Incorrecta o Falta.", "value" => "03",    "descripcion" => "Fecha de Acta Incorrecta o Falta."],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "Falta Hora o Incorrecta", "value" => "04",    "descripcion" => "Falta Hora o Incorrecta"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "Otros Motivos de Desestimacion", "value" => "05",    "descripcion" => "Otros Motivos de Desestimacion"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "CHAPA PATENTE ILEGIBLE", "value" => "06",    "descripcion" => "CHAPA PATENTE ILEGIBLE"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "FALTA LUGAR DE LA INFRACCION", "value" => "07",    "descripcion" => "FALTA LUGAR DE LA INFRACCION"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "ERROR AL CONSIGNAR EL MES DE LA FALTA", "value" => "08",    "descripcion" => "ERROR AL CONSIGNAR EL MES DE LA FALTA"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "ERROR AL TIPIFICAR LA FALTA", "value" => "09",    "descripcion" => "ERROR AL TIPIFICAR LA FALTA"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "FALTA DESCRIPCCION DE LA FALTA.", "value" => "10",    "descripcion" => "FALTA DESCRIPCCION DE LA FALTA."],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "DATOS DE DOMICILIO INSUFICIENTES.", "value" => "11",    "descripcion" => "DATOS DE DOMICILIO INSUFICIENTES."],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "FALTA SELLO Y/O FIRMA INSPECTOR", "value" => "12",    "descripcion" => "FALTA SELLO Y/O FIRMA INSPECTOR"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "Falta datos del propietario", "value" => "13",    "descripcion" => "Falta datos del propietario"],
            ["label" => "ACTA_DESEST_MOT", "nombre" => "falta indentificar al imputado", "value" => "14",    "descripcion" => "falta indentificar al imputado"],

            ["label" => "ESTADO_GENERAL", "nombre" => "HABILITADO", "value" => "S",    "descripcion" => "HABILITADO"],
            ["label" => "ESTADO_GENERAL", "nombre" => "DESHABILITADO", "value" => "N",    "descripcion" => "DESHABILITADO"],

            /* categoria infractor */
            ["label" => "CATEGORIA_INFRACTOR", "nombre" => "Conductor", "value" => "1",    "descripcion" => "Conductor"],
            ["label" => "CATEGORIA_INFRACTOR", "nombre" => "Poseedor", "value" => "2",    "descripcion" => "Poseedor"],
            ["label" => "CATEGORIA_INFRACTOR", "nombre" => "Testigo", "value" => "7",    "descripcion" => "Testigo"],
            ["label" => "CATEGORIA_INFRACTOR", "nombre" => "Responsable", "value" => "3",    "descripcion" => "Responsable"],
            ["label" => "CATEGORIA_INFRACTOR", "nombre" => "Resp.Semirremolque", "value" => "5",    "descripcion" => "Resp.Semirremolque"],
            ["label" => "CATEGORIA_INFRACTOR", "nombre" => "Resp.Camión", "value" => "4",    "descripcion" => "Resp.Camión"],
            ["label" => "CATEGORIA_INFRACTOR", "nombre" => "Atendido", "value" => "6",    "descripcion" => "Atendido"],

            /* categoria padron */
            ["label" => "CATEGORIA_PADRON", "nombre" => "Camión", "value" => "1",    "descripcion" => "Camión"],
            ["label" => "CATEGORIA_PADRON", "nombre" => "Semirremolque", "value" => "2",    "descripcion" => "Semirremolque"],
            /* 
            DOM_DOCUMENTO_TIPO	CUIL		7	CLAVE UNICA DE IDENTIFICACION LABORAL
            DOM_DOCUMENTO_TIPO	CUIT		6	CLAVE UNICA DE IDENTIFICACION TRIBUTARIA
            DOM_DOCUMENTO_TIPO	CI		4	CEDULA DE IDENTIDAD
            DOM_DOCUMENTO_TIPO	LC		3	LIBRETA CIVICA
            DOM_DOCUMENTO_TIPO	LE		2	LIBRETA DE ENROLAMIENTO
            DOM_DOCUMENTO_TIPO	PAS		5	PASAPORTE
            DOM_DOCUMENTO_TIPO	CF		8	CEDULA FEDERAL
            DOM_DOCUMENTO_TIPO	DNI		1	DOCUMENTO NACIONAL DE IDENTIDAD
            DOM_DOCUMENTO_TIPO	OT		9	OTRO
            DOM_DOCUMENTO_TIPO	EXT		10	EXTRANJERO
             */
            ["label" => "DOCUMENTO_TIPO", "nombre" => "CUIL", "value" => "7",    "descripcion" => "CLAVE UNICA DE IDENTIFICACION LABORAL"],
            ["label" => "DOCUMENTO_TIPO", "nombre" => "CUIT", "value" => "6",    "descripcion" => "CLAVE UNICA DE IDENTIFICACION TRIBUTARIA"],
            ["label" => "DOCUMENTO_TIPO", "nombre" => "CI", "value" => "4",    "descripcion" => "CEDULA DE IDENTIDAD"],
            ["label" => "DOCUMENTO_TIPO", "nombre" => "LC", "value" => "3",    "descripcion" => "LIBRETA CIVICA"],
            ["label" => "DOCUMENTO_TIPO", "nombre" => "LE", "value" => "2",    "descripcion" => "LIBRETA DE ENROLAMIENTO"],
            ["label" => "DOCUMENTO_TIPO", "nombre" => "PAS", "value" => "5",    "descripcion" => "PASAPORTE"],
            ["label" => "DOCUMENTO_TIPO", "nombre" => "CF", "value" => "8",    "descripcion" => "CEDULA FEDERAL"],
            ["label" => "DOCUMENTO_TIPO", "nombre" => "DNI", "value" => "1",    "descripcion" => "DOCUMENTO NACIONAL DE IDENTIDAD"],
            ["label" => "DOCUMENTO_TIPO", "nombre" => "OT", "value" => "9",    "descripcion" => "OTRO"],
            ["label" => "DOCUMENTO_TIPO", "nombre" => "EXT", "value" => "10",    "descripcion" => "EXTRANJERO"],

            /* 
            tipo padrones
            ZOO	NQN	Zoonosis
            INM	NQN	Inmuebles
            COM	NQN	Comercios
            AUT	NQN	Automotores
            MOT	NQN	Motovehículos
            */
            ["label" => "TIPO_PADRON", "nombre" => "Zoonosis", "value" => "ZOO",    "descripcion" => "Zoonosis"],
            ["label" => "TIPO_PADRON", "nombre" => "Inmuebles", "value" => "INM",    "descripcion" => "Inmuebles"],
            ["label" => "TIPO_PADRON", "nombre" => "Comercios", "value" => "COM",    "descripcion" => "Comercios"],
            ["label" => "TIPO_PADRON", "nombre" => "Automotores", "value" => "AUT",    "descripcion" => "Automotores"],
            ["label" => "TIPO_PADRON", "nombre" => "Motovehículos", "value" => "MOT",    "descripcion" => "Motovehículos"],







        ];

        foreach ($estados as $estado) {
            EstadosGenerales::updateOrCreate(
                ['nombre' => $estado['nombre'], 'value' => $estado['value'], 'descripcion' => $estado['descripcion']],
                [
                    'label' => $estado['label'],
                    'nombre' => $estado['nombre'],
                    'value' => $estado['value'],
                    'descripcion' => $estado['descripcion'],
                ]
            );
        }
    }
}
