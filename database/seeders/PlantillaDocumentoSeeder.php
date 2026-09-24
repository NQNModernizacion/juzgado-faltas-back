<?php

namespace Database\Seeders;

use App\Models\PlantillaDocumento;
use Illuminate\Database\Seeder;

class PlantillaDocumentoSeeder extends Seeder
{
    public function run(): void
    {
        PlantillaDocumento::updateOrCreate(
            ['codigo' => 'descargo'],
            [
                'nombre' => 'Descargo',
                'contenido_base_html' => '<p><strong>DESCARGO</strong></p><p><br></p><p>En la ciudad de Neuquén, a los {DIAS_FECHA} días del mes de {MES_FECHA} de {ANIO_FECHA}, comparece el/la Sr/a <strong>{NOMBRE_IMPUTADO}</strong>, {TIPO_DOC} Nº <strong>{DOC_IMPUTADO}</strong>, en su carácter de {CATEGORIA_IMPUTADO}, y en ejercicio de su defensa, presenta el siguiente descargo:</p><p><br></p><p>________________________________________________________________</p><p><br></p><div style="text-align: right; margin-top: 50px;"><p>_______________________<br>Firma del Compareciente</p></div>',
            ]
        );

        PlantillaDocumento::updateOrCreate(
            ['codigo' => 'rebeldia'],
            [
                'nombre' => 'Rebeldía',
                'contenido_base_html' => '<p><strong>DECLARACIÓN DE REBELDÍA</strong></p><p><br></p><p>En la ciudad de Neuquén, a los {DIAS_FECHA} días del mes de {MES_FECHA} de {ANIO_FECHA}.</p><p><strong>VISTO:</strong> Las actuaciones correspondientes al Acta Nº <strong>{NUMERO_ACTA}</strong>, Causa Nº <strong>{NUMERO_CAUSA}</strong> de fecha {FECHA_LABRADA}, seguida contra <strong>{NOMBRE_IMPUTADO}</strong>, {TIPO_DOC} Nº {DOC_IMPUTADO}.</p><p><br></p><p><strong>Y CONSIDERANDO:</strong> Que habiendo sido debidamente citado y emplazado el presunto infractor para comparecer ante este Tribunal a ejercer su derecho de defensa y formular descargo, ha vencido con exceso el término legal conferido sin que haya comparecido ni justificado su incomparecencia.</p><p><br></p><p><strong>RESUELVO:</strong> Declarar la <strong>REBELDÍA</strong> del imputado <strong>{NOMBRE_IMPUTADO}</strong> ({TIPO_DOC} Nº {DOC_IMPUTADO}), continuando las presentes actuaciones según su estado procesal.</p><p><br></p><div style="text-align: right; margin-top: 50px;"><p>_______________________<br>Firma Juez / Secretario</p></div>',
            ]
        );
    }
}
