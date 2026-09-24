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
    @endphp

    <div class="cuerpo-formulario-editable" style="margin-top: 25px; padding-top: 15px; border-top: 2px solid #333;">
        <p><strong>DESCARGO</strong></p>
        <p><br></p>
        <p>En la ciudad de Neuquén, a los {{ $diasFecha }} días del mes de {{ $mesFecha }} de {{ $anioFecha }}, comparece el/la Sr/a <strong>{{ $nombreImputado }}</strong>, {{ $tipoDoc }} Nº <strong>{{ $docImputado }}</strong>, en su carácter de imputado/a, y en ejercicio de su defensa, presenta el siguiente descargo:</p>
        <p><br></p>
        <p>________________________________________________________________</p>
        <p><br></p>
        <div style="text-align: right; margin-top: 50px;">
            <p>_______________________<br>Firma del Compareciente</p>
        </div>
    </div>
@endsection
