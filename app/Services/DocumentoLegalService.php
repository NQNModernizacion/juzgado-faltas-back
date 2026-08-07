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
                        'causa_anio' => $acta->year ?? \Carbon\Carbon::now()->year,
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
        $acta->loadMissing(['juzgado', 'juez', 'secretaria', 'infractores', 'infracciones', 'oficina', 'inspector1', 'inspector2', 'padrones.tipo', 'cautelares', 'calle', 'cruce']);

        $categoriasMap = \App\Models\EstadosGenerales::where('label', 'CATEGORIA_INFRACTOR')
            ->pluck('nombre', 'id')
            ->toArray();

        $autosList = [];
        foreach ($acta->infractores as $infractor) {
            // $catId = $infractor->pivot->categoria_infractor_id;
            // $catNombre = $categoriasMap[$catId] ?? '';
            // $suffix = $catNombre ? ' ,' . strtoupper(substr($catNombre, 0, 1)) : '';
            $autosList[] = strtoupper($infractor->nombre)/*  . $suffix */;
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

        // Nuevos campos y lógicas de caché/DNRPA
        $cautelaresText = $acta->cautelares->pluck('nombre')->map(fn($n) => strtoupper($n))->implode(', ');

        $direccionFalta = '';
        if ($acta->calle || $acta->lugar) {
            if ($acta->lugar) {
                $direccionFalta .= $acta->lugar;
            }
            if ($acta->calle) {
                $direccionFalta .= ($direccionFalta ? ' - ' : '') . $acta->calle->nombre;
            }
            if ($acta->numero_calle) {
                $direccionFalta .= ' N° ' . $acta->numero_calle;
            }
            if ($acta->cruce) {
                $direccionFalta .= ' y ' . $acta->cruce->nombre;
            }
        }

        $imputadoDocs = $acta->infractores->map(function ($i) {
            return $i->identificacion ?: $i->documento;
        })->filter()->unique()->implode(', ');

        $padronesFilas = [];
        $cacheDias = (int) env('CACHE_PADRON_DIAS', 30);
        $fechaLimite = now()->subDays($cacheDias);

        foreach ($acta->padrones as $padron) {
            $tipoValue = $padron->tipo->value ?? '';
            if ($tipoValue === 'AUT' || $tipoValue === 'MOT') {
                $patente = $padron->identificacion;
                $tieneCacheValida = $padron->fecha_actualizacion
                    && $padron->fecha_actualizacion->gt($fechaLimite)
                    && !empty($padron->data_cache);

                $dnrpaData = $padron->data_cache;

                if (!$tieneCacheValida) {
                    try {
                        $dnrpaData = consultar_aut_externo($patente);
                        $padron->update([
                            'data_cache' => $dnrpaData,
                            'fecha_actualizacion' => now(),
                            'nombre' => $dnrpaData['nombre']
                                ?? $dnrpaData['razon_social']
                                ?? ($dnrpaData['titular']['nombre'] ?? null)
                                ?? ($padron->nombre ?? 'VEHICULO ' . $patente),
                        ]);
                    } catch (\Throwable $e) {
                        \Illuminate\Support\Facades\Log::warning("Error consultando patente {$patente} en generación de carátula, se usará caché local si existe: " . $e->getMessage());
                    }
                }

                $padronesFilas[] = [
                    'tipo' => 'vehiculo',
                    'marca' => $dnrpaData['vehiculo']['marca'] ?? '',
                    'modelo' => $dnrpaData['vehiculo']['modelo'] ?? '',
                    'dominio' => $dnrpaData['dominio'] ?? $patente,
                ];
            } else {
                $padronesFilas[] = [
                    'tipo' => 'general',
                    'direccion' => $direccionFalta,
                    'documento' => $padron->identificacion /* ?: $imputadoDocs */,
                ];
            }
        }

        /*  if (empty($padronesFilas)) {
            $padronesFilas[] = [
                'tipo' => 'general',
                'direccion' => $direccionFalta,
                'documento' => $imputadoDocs,
            ];
        } */

        $autosLen = strlen($autosText);
        $autosFontSize = $autosLen > 60 ? '14pt' : ($autosLen > 30 ? '17pt' : '20pt');

        return Pdf::view('pdfs.caratula', [
            'acta' => $acta,
            'autosText' => $autosText,
            'autosFontSize' => $autosFontSize,
            'faltasText' => $faltasText,
            'oficinaResumida' => $oficinaResumida,
            'cautelaresText' => $cautelaresText,
            'padronesFilas' => $padronesFilas,
        ])
            ->format('a4')
            ->margins(15, 15, 15, 15);
    }
}
