@extends('formularios.base_precarga')

@section('contenido')
    @include('formularios.partials.ficha_acta')
    @include('formularios.partials.movimientos')
    @include('formularios.partials.estados_procesales')

    @php
        $primerImputado = $imputado ?? $acta->infractores->first();
        $nombreImputado = $primerImputado ? strtoupper($primerImputado->nombre) : '______________________';
        $docImputado = $primerImputado ? ($primerImputado->cuit ?: $primerImputado->documento ?: $primerImputado->identificacion) : '________________';
        $tipoDoc = ($primerImputado && $primerImputado->cuit) ? 'CUIT' : 'DNI';
        $categoriaImputado = 'imputado/a';

        $diasFecha = (string) $fechaActual->day;
        $mesFecha = $fechaActual->translatedFormat('F');
        $anioFecha = (string) $fechaActual->year;

        $numeroActa = (string) ($acta->numero_acta ?? '-');
        $numeroCausa = (string) ($acta->numero_causa ?? $acta->id);
        $fechaLabrada = $acta->fecha_labrada ? \Carbon\Carbon::parse($acta->fecha_labrada)->format('d/m/Y') : '-';

        $tags = [
            '{DIAS_FECHA}' => $diasFecha,
            '{MES_FECHA}' => $mesFecha,
            '{ANIO_FECHA}' => $anioFecha,
            '{NOMBRE_IMPUTADO}' => $nombreImputado,
            '{DOC_IMPUTADO}' => $docImputado,
            '{TIPO_DOC}' => $tipoDoc,
            '{CATEGORIA_IMPUTADO}' => $categoriaImputado,
            '{NUMERO_ACTA}' => $numeroActa,
            '{NUMERO_CAUSA}' => $numeroCausa,
            '{FECHA_LABRADA}' => $fechaLabrada,
        ];

        $htmlCuerpo = str_replace(array_keys($tags), array_values($tags), $plantilla->contenido_base_html ?? '');
    @endphp

    <div class="cuerpo-formulario-editable" style="margin-top: 25px; padding-top: 15px; border-top: 2px solid #333;">
        {!! $htmlCuerpo !!}
    </div>
@endsection
