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
        // 'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
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
            // ["label" => "ley", "nombre" => null, "value" => 0,    "descripcion" => null],
            // ["label" => "ley", "nombre" => "Ord 8833", "value" => 1,    "descripcion" => "Ord 8833"],
            ["label" => "ley", "nombre" => "Ley 12028", "value" => 2,    "descripcion" => "Ley 12028"],

            /* Estados */
            // ["label" => "estado", "nombre" => "Baja", "value" => "BA",    "descripcion" => "Baja"],
            // ["label" => "estado", "nombre" => "Género Causa", "value" => "C",    "descripcion" => "Género Causa"],
            // ["label" => "estado", "nombre" => "Notificado", "value" => "NOT",    "descripcion" => "Notificado"],
            // ["label" => "estado", "nombre" => "Sin Datos", "value" => "SD",    "descripcion" => "Sin Datos"],
            // FIRMO?
            // INTERVENCIÓN?
            // SECUESTRO?
            // CLAUSURA?
            // P. MORDEDOR?
            // RETUVO LICENCIA?
            // INHABILITACIÓN?

            ["label" => "estado", "nombre" => "FIRMO", "value" => "F",    "descripcion" => "FIRMO"],
            ["label" => "estado", "nombre" => "INTERVENCION", "value" => "I",    "descripcion" => "INTERVENCION"],
            ["label" => "estado", "nombre" => "SECUESTRO", "value" => "S",    "descripcion" => "SECUESTRO"],
            ["label" => "estado", "nombre" => "CLAUSURA", "value" => "C",    "descripcion" => "CLAUSURA"],
            ["label" => "estado", "nombre" => "P. MORDEDOR", "value" => "P",    "descripcion" => "P. MORDEDOR"],
            ["label" => "estado", "nombre" => "RETUVO LICENCIA", "value" => "R",    "descripcion" => "RETUVO LICENCIA"],
            ["label" => "estado", "nombre" => "INHABILITACIÓN", "value" => "H",    "descripcion" => "INHABILITACIÓN"],

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
            // moto, auto, camión, cuatriciclo, casa rodante
            ["label" => "CATEGORIA_PADRON", "nombre" => "Camión", "value" => "1",    "descripcion" => "Camión"],
            ["label" => "CATEGORIA_PADRON", "nombre" => "Semirremolque", "value" => "2",    "descripcion" => "Semirremolque"],
            ["label" => "CATEGORIA_PADRON", "nombre" => "Auto", "value" => "3",    "descripcion" => "Auto"],
            ["label" => "CATEGORIA_PADRON", "nombre" => "Moto", "value" => "4",    "descripcion" => "Moto"],
            ["label" => "CATEGORIA_PADRON", "nombre" => "Cuatriciclo", "value" => "5",    "descripcion" => "Cuatriciclo"],
            ["label" => "CATEGORIA_PADRON", "nombre" => "Casa rodante", "value" => "6",    "descripcion" => "Casa rodante"],

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

            /* INFRACCION_TIPO */
            ['label' => 'INFRACCION_TIPO', 'value' => '99', 'nombre' => "FOTOMULTAS", 'descripcion' => "FOTOMULTAS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '00', 'nombre' => "COSTAS", 'descripcion' => "COSTAS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '10', 'nombre' => "FALTAS CONTRA AUTORIDAD MUNICIPAL", 'descripcion' => "FALTAS CONTRA AUTORIDAD MUNICIPAL"],
            ['label' => 'INFRACCION_TIPO', 'value' => '20', 'nombre' => "SANID.eHIG.y SE.COMERCIOS,IND.yACT.ASIM.", 'descripcion' => "SANID.eHIG.y SE.COMERCIOS,IND.yACT.ASIM."],
            ['label' => 'INFRACCION_TIPO', 'value' => '21', 'nombre' => "DE LA SANIDAD E HIGIENE EN GRAL.", 'descripcion' => "DE LA SANIDAD E HIGIENE EN GRAL."],
            ['label' => 'INFRACCION_TIPO', 'value' => '22', 'nombre' => "DE LA SANIDAD E HIGIENE ALIMENTARIA", 'descripcion' => "DE LA SANIDAD E HIGIENE ALIMENTARIA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '23', 'nombre' => "DE LA SANIDAD E HIGIENE DE LA VIA PUBL.", 'descripcion' => "DE LA SANIDAD E HIGIENE DE LA VIA PUBL."],
            ['label' => 'INFRACCION_TIPO', 'value' => '24', 'nombre' => "RESIDUOS PATOGENOS TOXICOS Y RADIOACTIVO", 'descripcion' => "RESIDUOS PATOGENOS TOXICOS Y RADIOACTIVO"],
            ['label' => 'INFRACCION_TIPO', 'value' => '25', 'nombre' => "LA PRESERVACION DE LA CALIDAD AMBIENTAL", 'descripcion' => "LA PRESERVACION DE LA CALIDAD AMBIENTAL"],
            ['label' => 'INFRACCION_TIPO', 'value' => '26', 'nombre' => "CONTAMINACION AMBIENTAL POR ACTIV.HIDROC", 'descripcion' => "CONTAMINACION AMBIENTAL POR ACTIV.HIDROC"],
            ['label' => 'INFRACCION_TIPO', 'value' => '27', 'nombre' => "LA HIGIENE MORTUORIA", 'descripcion' => "LA HIGIENE MORTUORIA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '30', 'nombre' => "DE LA OBRAS Y DEMOLICIONES", 'descripcion' => "DE LA OBRAS Y DEMOLICIONES"],
            ['label' => 'INFRACCION_TIPO', 'value' => '31', 'nombre' => "LA SEGURIDAD Y EL BIENESTAR", 'descripcion' => "LA SEGURIDAD Y EL BIENESTAR"],
            ['label' => 'INFRACCION_TIPO', 'value' => '32', 'nombre' => "LAS OBRAS Y DEMOLICIONES", 'descripcion' => "LAS OBRAS Y DEMOLICIONES"],
            ['label' => 'INFRACCION_TIPO', 'value' => '33', 'nombre' => "LAS OBRAS EN LA VIA PUBLICA", 'descripcion' => "LAS OBRAS EN LA VIA PUBLICA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '34', 'nombre' => "LAS INDUSTRIAS,COMERCIOS Y ACTIV.ASIMIL.", 'descripcion' => "LAS INDUSTRIAS,COMERCIOS Y ACTIV.ASIMIL."],
            ['label' => 'INFRACCION_TIPO', 'value' => '35', 'nombre' => "LOS ESPECTACULOS PUBLICOS", 'descripcion' => "LOS ESPECTACULOS PUBLICOS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '36', 'nombre' => "LOS NATATORIOS DE USO PUBLICO", 'descripcion' => "LOS NATATORIOS DE USO PUBLICO"],
            ['label' => 'INFRACCION_TIPO', 'value' => '37', 'nombre' => "LA VIA PUBLICA Y LUGARES PUBLICOS", 'descripcion' => "LA VIA PUBLICA Y LUGARES PUBLICOS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '38', 'nombre' => "LA PUBLICIDAD Y LA PROPAGANDA", 'descripcion' => "LA PUBLICIDAD Y LA PROPAGANDA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '41', 'nombre' => "TRANSITO. NORMAS SOBRE DOCUMENTACION", 'descripcion' => "TRANSITO. NORMAS SOBRE DOCUMENTACION"],
            ['label' => 'INFRACCION_TIPO', 'value' => '42', 'nombre' => "TRANSITO. PARTES DEL VEHICULO", 'descripcion' => "TRANSITO. PARTES DEL VEHICULO"],
            ['label' => 'INFRACCION_TIPO', 'value' => '43', 'nombre' => "TRANSITO. CIRCULACION Y ESTACIONAMIENTO", 'descripcion' => "TRANSITO. CIRCULACION Y ESTACIONAMIENTO"],
            ['label' => 'INFRACCION_TIPO', 'value' => '44', 'nombre' => "TRANSITO. VIAS MULTICARRILES", 'descripcion' => "TRANSITO. VIAS MULTICARRILES"],
            ['label' => 'INFRACCION_TIPO', 'value' => '45', 'nombre' => "DEL TRANSPORTE DE CARGA", 'descripcion' => "DEL TRANSPORTE DE CARGA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '46', 'nombre' => "DE LA ESTACION TERMINAL DE OMNIBUS", 'descripcion' => "DE LA ESTACION TERMINAL DE OMNIBUS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '51', 'nombre' => "SERV.deAUTOMOT.C/TAXIMETRO y AUTOS REMIS", 'descripcion' => "SERV.deAUTOMOT.C/TAXIMETRO y AUTOS REMIS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '52', 'nombre' => "SERVICIO DE TRANSPORTE ESCOLAR", 'descripcion' => "SERVICIO DE TRANSPORTE ESCOLAR"],
            ['label' => 'INFRACCION_TIPO', 'value' => '53', 'nombre' => "SERVICIO DE TRANSP.COLECTIVO DE PASAJERO", 'descripcion' => "SERVICIO DE TRANSP.COLECTIVO DE PASAJERO"],
            ['label' => 'INFRACCION_TIPO', 'value' => '54', 'nombre' => "TRANSPORTE DE CARGAS", 'descripcion' => "TRANSPORTE DE CARGAS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '55', 'nombre' => "SERV.deTRANSP.deCARGAS LIVIANAS-TAXIFLET", 'descripcion' => "SERV.deTRANSP.deCARGAS LIVIANAS-TAXIFLET"],
            ['label' => 'INFRACCION_TIPO', 'value' => '56', 'nombre' => "LA ESTACION TERMINAL DE OMNIBUS", 'descripcion' => "LA ESTACION TERMINAL DE OMNIBUS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '57', 'nombre' => "TRANS.PRIV.PERS.C/CHOF.Y ALQU.S/CHOFER", 'descripcion' => "TRANS.PRIV.PERS.C/CHOF.Y ALQU.S/CHOFER"],
            ['label' => 'INFRACCION_TIPO', 'value' => '60', 'nombre' => "DE LAS FALTAS C/AUTORIDAD MUNICIPAL", 'descripcion' => "DE LAS FALTAS C/AUTORIDAD MUNICIPAL"],
            ['label' => 'INFRACCION_TIPO', 'value' => '61', 'nombre' => "DEL PROCEDIM.DE EVALUACIÓN AMBIENTAL", 'descripcion' => "DEL PROCEDIM.DE EVALUACIÓN AMBIENTAL"],
            ['label' => 'INFRACCION_TIPO', 'value' => '62', 'nombre' => "DE LAS ACTIVIDADES HIDROCARBURÍFERAS", 'descripcion' => "DE LAS ACTIVIDADES HIDROCARBURÍFERAS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '63', 'nombre' => "DE LAS ANTENAS", 'descripcion' => "DE LAS ANTENAS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '64', 'nombre' => "DE LAS ACCIONES SOBRE EL SOPORTE NATURAL", 'descripcion' => "DE LAS ACCIONES SOBRE EL SOPORTE NATURAL"],
            ['label' => 'INFRACCION_TIPO', 'value' => '65', 'nombre' => "DE LOS RESIDUOS SÓLIDOS Y EFLUENTES", 'descripcion' => "DE LOS RESIDUOS SÓLIDOS Y EFLUENTES"],
            ['label' => 'INFRACCION_TIPO', 'value' => '66', 'nombre' => "DE LOS RES.PATÓGENOS,TÓXICOS Y/O RADIOAC", 'descripcion' => "DE LOS RES.PATÓGENOS,TÓXICOS Y/O RADIOAC"],
            ['label' => 'INFRACCION_TIPO', 'value' => '67', 'nombre' => "DE LAS EMISIONES Y RADIACIONES", 'descripcion' => "DE LAS EMISIONES Y RADIACIONES"],
            ['label' => 'INFRACCION_TIPO', 'value' => '68', 'nombre' => "DE LA REMEDIACIÓN AMBIENTAL", 'descripcion' => "DE LA REMEDIACIÓN AMBIENTAL"],
            ['label' => 'INFRACCION_TIPO', 'value' => '69', 'nombre' => "DE LA SANIDAD E HIGIENE PÚBLICA", 'descripcion' => "DE LA SANIDAD E HIGIENE PÚBLICA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '70', 'nombre' => "DE LA SANID.HIG.Y TENENC.RESP.D/ANIMAL.", 'descripcion' => "DE LA SANID.HIG.Y TENENC.RESP.D/ANIMAL."],
            ['label' => 'INFRACCION_TIPO', 'value' => '71', 'nombre' => "DE LA SANIDAD E HIGIENE ALIMENTARIA", 'descripcion' => "DE LA SANIDAD E HIGIENE ALIMENTARIA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '72', 'nombre' => "DE LA SANID. E HIGIENE EN LA VIA PÚBLICA", 'descripcion' => "DE LA SANID. E HIGIENE EN LA VIA PÚBLICA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '73', 'nombre' => "DE LOS NATATORIOS DE USO PÚBLICO", 'descripcion' => "DE LOS NATATORIOS DE USO PÚBLICO"],
            ['label' => 'INFRACCION_TIPO', 'value' => '74', 'nombre' => "DE LA HIGIENE MORTUORIA", 'descripcion' => "DE LA HIGIENE MORTUORIA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '75', 'nombre' => "DE LA SEGURIDAD EN GENERAL", 'descripcion' => "DE LA SEGURIDAD EN GENERAL"],
            ['label' => 'INFRACCION_TIPO', 'value' => '76', 'nombre' => "DE LAS OBRAS Y DEMOLICIONES", 'descripcion' => "DE LAS OBRAS Y DEMOLICIONES"],
            ['label' => 'INFRACCION_TIPO', 'value' => '77', 'nombre' => "DE LOS LOTEOS", 'descripcion' => "DE LOS LOTEOS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '78', 'nombre' => "DE LAS OBRAS EN LA VÍA PÚBLICA", 'descripcion' => "DE LAS OBRAS EN LA VÍA PÚBLICA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '79', 'nombre' => "DE LA VÍA PÚBLICA Y LUGARES PÚBLICOS", 'descripcion' => "DE LA VÍA PÚBLICA Y LUGARES PÚBLICOS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '80', 'nombre' => "DE LAS INDUSTRIAS,COM.Y ACT.ASIMILABLES", 'descripcion' => "DE LAS INDUSTRIAS,COM.Y ACT.ASIMILABLES"],
            ['label' => 'INFRACCION_TIPO', 'value' => '81', 'nombre' => "DE LA VENTA DE ARTÍCULOS PIROTÉCNICOS", 'descripcion' => "DE LA VENTA DE ARTÍCULOS PIROTÉCNICOS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '82', 'nombre' => "DE LA VENTA Y/O CONSUMO DE ALCOHOL", 'descripcion' => "DE LA VENTA Y/O CONSUMO DE ALCOHOL"],
            ['label' => 'INFRACCION_TIPO', 'value' => '83', 'nombre' => "DE LOS ESPECTÁCULOS PÚBLICOS", 'descripcion' => "DE LOS ESPECTÁCULOS PÚBLICOS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '84', 'nombre' => "DE LA PUBLICIDAD Y LA PROPAGANDA", 'descripcion' => "DE LA PUBLICIDAD Y LA PROPAGANDA"],
            ['label' => 'INFRACCION_TIPO', 'value' => '85', 'nombre' => "DE LAS CONTRAV.A LAS NORMAS S/DOCUMENTAC", 'descripcion' => "DE LAS CONTRAV.A LAS NORMAS S/DOCUMENTAC"],
            ['label' => 'INFRACCION_TIPO', 'value' => '86', 'nombre' => "DE L/CONTRAV.REF.A LAS PARTES DEL VEHIC.", 'descripcion' => "DE L/CONTRAV.REF.A LAS PARTES DEL VEHIC."],
            ['label' => 'INFRACCION_TIPO', 'value' => '87', 'nombre' => "DE L/CONTRAV.REF.A LA CIRCUL.Y ESTACION.", 'descripcion' => "DE L/CONTRAV.REF.A LA CIRCUL.Y ESTACION."],
            ['label' => 'INFRACCION_TIPO', 'value' => '88', 'nombre' => "VIAS MULTICARRILES", 'descripcion' => "VIAS MULTICARRILES"],
            ['label' => 'INFRACCION_TIPO', 'value' => '89', 'nombre' => "DEL SERV.DE AUTOM.CON TAXIM.Y REMISSES", 'descripcion' => "DEL SERV.DE AUTOM.CON TAXIM.Y REMISSES"],
            ['label' => 'INFRACCION_TIPO', 'value' => '90', 'nombre' => "DEL SERV.DE TRANSPORTE ESCOLAR", 'descripcion' => "DEL SERV.DE TRANSPORTE ESCOLAR"],
            ['label' => 'INFRACCION_TIPO', 'value' => '91', 'nombre' => "DEL SERV.DE TRANSP.COLECT.DE PASAJEROS", 'descripcion' => "DEL SERV.DE TRANSP.COLECT.DE PASAJEROS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '92', 'nombre' => "DEL TRANSPORTE DE CARGAS", 'descripcion' => "DEL TRANSPORTE DE CARGAS"],
            ['label' => 'INFRACCION_TIPO', 'value' => '93', 'nombre' => "DEL SERV.DE TRANS.D/CARGAS LIV.TAXI FLET", 'descripcion' => "DEL SERV.DE TRANS.D/CARGAS LIV.TAXI FLET"],
            ['label' => 'INFRACCION_TIPO', 'value' => '94', 'nombre' => "DE LA ESTACIÓN TERMINAL DE ÓMNIBUS", 'descripcion' => "DE LA ESTACIÓN TERMINAL DE ÓMNIBUS"],
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
