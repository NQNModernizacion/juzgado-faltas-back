<?php

namespace App\Services;

use App\Models\DocumentoLegal;
use App\Models\Causa;
use Illuminate\Support\Facades\DB;
use Spatie\LaravelPdf\Facades\Pdf;

class DocumentoLegalService
{
    public function crearDocumento(array $data)
    {
        return DB::transaction(function () use ($data) {
            $actaId = $data['acta_id'] ?? $data['causa_id'] ?? null;
            if ($actaId && empty($data['metadata'])) {
                $acta = \App\Models\Acta::with(['juzgado'])->find($actaId);
                if ($acta) {
                    $data['metadata'] = [
                        'juzgado_nro' => $acta->juzgado->numero_juzgado ?? '1',
                        'juzgado_direccion' => 'MITRE Nº 461',
                        'causa_anio' => $acta->year ?? date('Y'),
                        'causa_nro' => $acta->numero_causa ?? $acta->id,
                        'ciudad' => 'NEUQUEN',
                    ];
                }
            }

            $documento = DocumentoLegal::create($data);
            return $documento;
        });
    }

    public function generarPdfSpatie(DocumentoLegal $documento)
    {
        $metadata = $documento->metadata ?? [];

        // Pasamos metadata como variables de la vista del header
        $headerHtml = view('pdfs.partials.header', $metadata)->render();
        $footerHtml = view('pdfs.partials.footer')->render();

        return Pdf::view('pdfs.documento_base', ['documento' => $documento])
            ->format('a4')
            ->headerHtml($headerHtml)
            ->footerHtml($footerHtml)
            ->margins(30, 20, 30, 20); // Márgenes: top, right, bottom, left en milimetros
    }

    public function crearCaratula($acta)
    {
        $acta->loadMissing(['juzgado', 'juez', 'secretaria', 'infractores', 'infracciones', 'oficina', 'inspector1', 'inspector2', 'padrones']);

        $categoriasMap = \App\Models\EstadosGenerales::where('label', 'CATEGORIA_INFRACTOR')
            ->pluck('nombre', 'id')
            ->toArray();

        $autosList = [];
        foreach ($acta->infractores as $infractor) {
            $catId = $infractor->pivot->categoria_infractor_id;
            $catNombre = $categoriasMap[$catId] ?? '';
            $suffix = $catNombre ? ' ,' . strtoupper(substr($catNombre, 0, 1)) : '';
            $autosList[] = strtoupper($infractor->nombre) . $suffix;
        }

        if (empty($autosList)) {
            foreach ($acta->padrones as $padron) {
                $autosList[] = strtoupper($padron->nombre) . ' ,P';
            }
        }

        $autosText = implode(' ; ', $autosList);

        $faltasText = $acta->infracciones->map(function ($infraccion) {
            $art = $infraccion->articulo ?? '';
            if (is_numeric($art)) {
                return (string) (int) $art;
            }
            return $art;
        })->filter()->unique()->implode(', ');

        $oficinaResumida = strtoupper($acta->oficina->descripcion_resumida ?? $acta->oficina->descripcion ?? 'FALTAS');

        return Pdf::view('pdfs.caratula', [
            'acta' => $acta,
            'autosText' => $autosText,
            'faltasText' => $faltasText,
            'oficinaResumida' => $oficinaResumida
        ])
            ->format('a4')
            ->margins(15, 15, 15, 15); // Carátula con márgenes de 15mm y sin cabezal/pie del documento base
    }
}
