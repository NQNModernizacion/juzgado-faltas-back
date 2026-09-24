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
        $diasFecha = (string) $fechaActual->day;
        $mesFecha = $fechaActual->translatedFormat('F');
        $anioFecha = (string) $fechaActual->year;
        $numeroActa = (string) ($acta->numero_acta ?? '-');
        $numeroCausa = (string) ($acta->numero_causa ?? $acta->id);
        $fechaLabrada = $acta->fecha_labrada ? \Carbon\Carbon::parse($acta->fecha_labrada)->format('d/m/Y') : '-';
    @endphp

    <div class="cuerpo-formulario-editable" style="margin-top: 25px; padding-top: 15px; border-top: 2px solid #333;">
        <p><strong>DECLARACIÓN DE REBELDÍA</strong></p>
        <p><br></p>
        <p>En la ciudad de Neuquén, a los {{ $diasFecha }} días del mes de {{ $mesFecha }} de {{ $anioFecha }}.</p>
        <p><strong>VISTO:</strong> Las actuaciones correspondientes al Acta Nº <strong>{{ $numeroActa }}</strong>, Causa Nº <strong>{{ $numeroCausa }}</strong> de fecha {{ $fechaLabrada }}, seguida contra <strong>{{ $nombreImputado }}</strong>, {{ $tipoDoc }} Nº {{ $docImputado }}.</p>
        <p><br></p>
        <p><strong>Y CONSIDERANDO:</strong> Que habiendo sido debidamente citado y emplazado el presunto infractor para comparecer ante este Tribunal a ejercer su derecho de defensa y formular descargo, ha vencido con exceso el término legal conferido sin que haya comparecido ni justificado su incomparecencia.</p>
        <p><br></p>
        <p><strong>RESUELVO:</strong> Declarar la <strong>REBELDÍA</strong> del imputado <strong>{{ $nombreImputado }}</strong> ({{ $tipoDoc }} Nº {{ $docImputado }}), continuando las presentes actuaciones según su estado procesal.</p>
        <p><br></p>
        <div style="text-align: right; margin-top: 50px;">
            <p>_______________________<br>Firma Juez / Secretario</p>
        </div>
    </div>
@endsection
