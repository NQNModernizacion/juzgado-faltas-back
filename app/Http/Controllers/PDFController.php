<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Facades\Storage;
use Spatie\Browsershot\Browsershot;
use Spatie\LaravelPdf\Enums\Format;

use function Spatie\LaravelPdf\Support\pdf;

class PDFController extends Controller
{
    public function pdf()
    {
        // $pdfController = new PDFController;
        $pdfGenerado = $this->generatePDF('welcome', [], 2);
        return sendResponse($pdfGenerado);
    }
    /**
     * FunciÃ³n para generar un cierto pdf
     * @param string $nombre_vista
     * @param string $obj_info
     * @param int $mode [1(guardar), 2(base64), 3(download), 4(guardar y base64), 5(guardar y download)]
     * @param string $path
     * @param array $headers ['header'=>'nombre_vista', 'footer'=>'nombre_vista']
     */
    public function generatePDF($nombre_vista, $obj_info, $mode = 1, $path = null, $headers = [])
    {
        $pdf = pdf()
            ->view($nombre_vista, ['data' => $obj_info])
            ->format(Format::A4)
            ->paperSize(210, 297, 'mm')
            ->margins(0, 0, 0, 0);
        //Enviroment local o replica/prod

        if (env('APP_ENV') == 'local') {
            $pdf->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->setNodeBinary(env('NODE_PATH'));
                $browsershot->setNpmBinary(env('NPM_PATH'));
            });
        } else {
            $pdf->withBrowsershot(function (Browsershot $browsershot) {
                $browsershot->noSandbox();
            });
        }
        //Ver si tiene vista de header
        if (array_key_exists('header', $headers) && $headers['header'] != null) {
            $pdf->headerView($headers['header']);
        }
        //Ver si tiene vista de footer
        if (array_key_exists('footer', $headers) && $headers['footer'] != null) {
            $pdf->footerView($headers['footer']);
        }
        switch ($mode) {
            case 1:
                //Se guarda y se devuelve el path
                $pdf_final = $pdf->disk('serverdata')->save($path);
                /* if (env('APP_ENV') == 'produccion') {
                    $pdf->disk('serverdata_replica')->save($path);
                } */
                break;

            case 2:
                //Se devuelve base 64
                $pdf_final = 'data:application/pdf;base64,' . $pdf->base64();
                break;
            case 3:
                //Se devuelve download
                $pdf_final = $pdf->download();
                break;
            case 4:
                //Se guarda y devuelve base64
                $pdf->disk('serverdata')->save($path);
                /* if (env('APP_ENV') == 'produccion') {
                    $pdf->disk('serverdata_replica')->save($path);
                } */
                $pdf_final = 'data:application/pdf;base64,' . $pdf->base64();
                break;
            case 5:
                //Se guarda y download
                $pdf->disk('serverdata')->save($path);
                /* if (env('APP_ENV') == 'produccion') {
                    $pdf->disk('serverdata_replica')->save($path);
                } */
                $pdf_final = $pdf->download();

            default:
                $pdf_final = null;
                break;
        }
        return $pdf_final;
    }
}
