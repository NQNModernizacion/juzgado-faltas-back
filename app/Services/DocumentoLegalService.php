<?php

namespace App\Services;

use App\Models\DocumentoLegal;
use App\Models\Causa;
use App\Models\Acta;
use App\Models\PlantillaDocumento;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

            // GESTIÓN DE REEMPLAZO AUTOMÁTICO:
            // Si viene un documento_reemplazado_id explícito, marcar ese documento como 'reemplazado'.
            // Si no viene, pero ya existen documentos activos para este acta y tipo, encontrar el anterior activo,
            // enlazarlo como documento_reemplazado_id y marcar los anteriores como 'reemplazado'.
            $tipo = $data['tipo'] ?? null;
            $reemplazadoId = $data['documento_reemplazado_id'] ?? null;

            if ($actaId && $tipo) {
                if ($reemplazadoId) {
                    DocumentoLegal::where('id', $reemplazadoId)
                        ->where('acta_id', $actaId)
                        ->update(['estado' => 'reemplazado']);
                } else {
                    $docAnteriorActivo = DocumentoLegal::where('acta_id', $actaId)
                        ->where('tipo', $tipo)
                        ->where('estado', 'activo')
                        ->latest('id')
                        ->first();

                    if ($docAnteriorActivo) {
                        $data['documento_reemplazado_id'] = $docAnteriorActivo->id;
                        $docAnteriorActivo->update(['estado' => 'reemplazado']);
                    }
                }

                // Garantizar que ningún otro documento previo del mismo tipo quede como 'activo'
                if (!empty($data['documento_reemplazado_id'])) {
                    DocumentoLegal::where('acta_id', $actaId)
                        ->where('tipo', $tipo)
                        ->where('estado', 'activo')
                        ->where('id', '!=', $data['documento_reemplazado_id'])
                        ->update(['estado' => 'reemplazado']);
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

    /**
     * Genera el nombre de archivo estandarizado con la nomenclatura judicial:
     * {plantilla}_{nro_juzgado}-{año}-{numero_causa}.pdf
     */
    public function generarNombreArchivo(DocumentoLegal $documento): string
    {
        $documento->loadMissing(['plantilla', 'acta.juzgado']);

        $tipo = Str::slug($documento->plantilla?->codigo ?? $documento->tipo ?? 'documento', '_');

        $acta = $documento->acta;
        $juzgadoNro = $acta?->juzgado?->numero_juzgado
            ?? $acta?->numero_juzgado_id
            ?? ($documento->metadata['juzgado_nro'] ?? null)
            ?? '1';

        $anio = $acta?->fecha_labrada
            ? Carbon::parse($acta->fecha_labrada)->format('Y')
            : ($acta?->year ?? ($documento->metadata['causa_anio'] ?? null) ?? Carbon::now()->year);

        $causaNro = $acta?->numero_causa
            ?? ($documento->metadata['causa_nro'] ?? null)
            ?? $acta?->id
            ?? $documento->id;

        return "{$tipo}_{$juzgadoNro}-{$anio}-{$causaNro}.pdf";
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

    /**
     * Genera el HTML precargado completo para el editor WYSIWYG del frontend utilizando vistas Blade nativas.
     */
    public function generarHtmlPrecarga(Acta $acta, PlantillaDocumento $plantilla): string
    {
        $acta->loadMissing([
            'juzgado', 'juez', 'secretaria', 'juezSubrogante', 'secretariaSubrogante',
            'oficina', 'calle', 'cruce', 'inspector1', 'inspector2',
            'cautelares', 'padrones.tipo', 'infractores', 'infracciones',
            'movimientos.oficinaOrigen', 'movimientos.oficinaDestino',
            'estadosProcesales'
        ]);

        $vista = view()->exists("formularios.plantillas.{$plantilla->codigo}")
            ? "formularios.plantillas.{$plantilla->codigo}"
            : "formularios.plantillas.default";

        $fechaActual = Carbon::now()->locale('es');
        $imputado = $acta->infractores->first();

        return view($vista, [
            'acta' => $acta,
            'plantilla' => $plantilla,
            'imputado' => $imputado,
            'fechaActual' => $fechaActual,
        ])->render();
    }

    /**
     * Fusiona los datos vigentes del expediente con el texto previamente redactado en un documento anterior.
     */
    public function reemitirConDatosActuales(DocumentoLegal $docPrevio): array
    {
        $docPrevio->loadMissing(['acta', 'plantilla']);
        $acta = $docPrevio->acta;
        $plantilla = $docPrevio->plantilla;

        if (!$acta) {
            throw new \DomainException('No se encontró el acta asociada al documento.');
        }

        if (!$plantilla) {
            $plantilla = PlantillaDocumento::where('codigo', $docPrevio->tipo)->first();
            if (!$plantilla) {
                throw new \DomainException('No se encontró la plantilla para reemitir este documento.');
            }
        }

        // 1. Generar HTML fresco con los datos y movimientos vigentes del acta
        $htmlFresco = $this->generarHtmlPrecarga($acta, $plantilla);

        // 2. Extraer el cuerpo redactado del documento anterior
        $htmlAnterior = $docPrevio->contenido_html;
        $cuerpoRedactado = null;

        if (preg_match('/<div[^>]*class=["\'][^"\']*cuerpo-formulario-editable[^"\']*["\'][^>]*>(.*?)<\/div>\s*$/s', $htmlAnterior, $matches)) {
            $cuerpoRedactado = $matches[1];
        }

        // 3. Reemplazar en el HTML fresco el cuerpo redactado
        $htmlFusionado = $htmlFresco;
        if ($cuerpoRedactado !== null) {
            $htmlFusionado = preg_replace(
                '/(<div[^>]*class=["\'][^"\']*cuerpo-formulario-editable[^"\']*["\'][^>]*>)(.*?)(<\/div>\s*$)/s',
                '${1}' . $cuerpoRedactado . '${3}',
                $htmlFresco
            );
        }

        return [
            'acta_id' => $acta->id,
            'plantilla_id' => $plantilla->id,
            'plantilla_codigo' => $plantilla->codigo,
            'plantilla_nombre' => $plantilla->nombre,
            'tipo' => $plantilla->codigo,
            'documento_reemplazado_id' => $docPrevio->id,
            'contenido_html' => $htmlFusionado,
        ];
    }
}
