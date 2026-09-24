@php
    $nroCausa = (string) ($acta->numero_causa ?? $acta->id);
    $anioCausa = (string) ($acta->year ?? \Carbon\Carbon::now()->year);
    $nroActa = (string) ($acta->numero_acta ?? '-');
    $fechaLabrada = $acta->fecha_labrada ? \Carbon\Carbon::parse($acta->fecha_labrada)->format('d/m/Y H:i') : '-';
    $oficinaDesc = $acta->oficina->descripcion ?? $acta->oficina->descripcion_resumida ?? '-';
    $juzgadoDesc = $acta->juzgado ? 'Juzgado Nº ' . ($acta->juzgado->numero_juzgado ?? $acta->juzgado->id) : '-';

    $juezNombre = $acta->juez->nombre ?? '-';
    if ($acta->juezSubrogante) {
        $juezNombre .= ' (Subrogante: ' . $acta->juezSubrogante->nombre . ')';
    }

    $secNombre = $acta->secretaria->nombre ?? '-';
    if ($acta->secretariaSubrogante) {
        $secNombre .= ' (Subrogante: ' . $acta->secretariaSubrogante->nombre . ')';
    }

    $inspList = array_filter([$acta->inspector1->nombre ?? null, $acta->inspector2->nombre ?? null]);
    $inspectoresDesc = !empty($inspList) ? implode(' | ', $inspList) : '-';

    $cautelaresDesc = $acta->cautelares->isNotEmpty() 
        ? $acta->cautelares->pluck('nombre')->implode(', ') 
        : 'Ninguna';

    $partesUbicacion = [];
    if ($acta->lugar) {
        $partesUbicacion[] = $acta->lugar;
    }
    if ($acta->calle) {
        $calleStr = $acta->calle->nombre;
        if ($acta->calle->codigo) {
            $calleStr .= ' (Cód. ' . $acta->calle->codigo . ')';
        }
        $partesUbicacion[] = $calleStr;
    }
    if ($acta->numero_calle) {
        $partesUbicacion[] = 'Nº ' . $acta->numero_calle;
    }
    if ($acta->cruce) {
        $partesUbicacion[] = 'esq. ' . $acta->cruce->nombre;
    }
    $direccionFalta = !empty($partesUbicacion) ? implode(' - ', $partesUbicacion) : '-';

    $imputadosDesc = $acta->infractores->map(function ($inf) {
        $doc = $inf->cuit ?: $inf->documento ?: $inf->identificacion ?: 'S/D';
        return $inf->nombre . ' (' . $doc . ')';
    })->implode(' ; ') ?: '-';

    $infraccionesDesc = $acta->infracciones->map(function ($inf) {
        $art = $inf->articulo ? 'Art. ' . $inf->articulo : '';
        $inc = $inf->inciso ? ' Inc. ' . $inf->inciso : '';
        $desc = $inf->descripcion ? ' - ' . $inf->descripcion : '';
        return trim($art . $inc . $desc);
    })->filter()->implode(' ; ') ?: '-';

    $padronesDesc = $acta->padrones->map(function ($padron) {
        $tipo = $padron->tipo->nombre ?? $padron->tipo->value ?? 'Padrón';
        $id = $padron->identificacion ?? '-';
        $extra = '';
        if (!empty($padron->data_cache['vehiculo'])) {
            $v = $padron->data_cache['vehiculo'];
            $extra = ' [' . ($v['marca'] ?? '') . ' ' . ($v['modelo'] ?? '') . ']';
        }
        return $tipo . ': ' . $id . $extra;
    })->implode(' ; ') ?: '-';
@endphp

<div style="border: 1px solid #aaa; padding: 12px; margin-bottom: 20px; background-color: #fafafa; border-radius: 4px;">
    <h3 style="margin-top: 0; margin-bottom: 10px; font-size: 12pt; text-align: center; text-transform: uppercase; border-bottom: 1px solid #ccc; padding-bottom: 5px;">
        Datos de la Causa y Acta
    </h3>
    <table style="width: 100%; border-collapse: collapse; font-size: 9.5pt;">
        <tr>
            <td style="padding: 4px 6px; width: 25%;"><strong>Causa Nº:</strong> {{ $nroCausa }}/{{ $anioCausa }}</td>
            <td style="padding: 4px 6px; width: 25%;"><strong>Acta Nº:</strong> {{ $nroActa }}</td>
            <td style="padding: 4px 6px; width: 25%;"><strong>Fecha Labrado:</strong> {{ $fechaLabrada }}</td>
            <td style="padding: 4px 6px; width: 25%;"><strong>Oficina:</strong> {{ $oficinaDesc }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 6px;"><strong>Juzgado:</strong> {{ $juzgadoDesc }}</td>
            <td style="padding: 4px 6px;"><strong>Juez:</strong> {{ $juezNombre }}</td>
            <td style="padding: 4px 6px;" colspan="2"><strong>Secretaría:</strong> {{ $secNombre }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 6px;" colspan="2"><strong>Inspectores:</strong> {{ $inspectoresDesc }}</td>
            <td style="padding: 4px 6px;" colspan="2"><strong>Medidas Cautelares:</strong> {{ $cautelaresDesc }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 6px;" colspan="4"><strong>Lugar del Hecho:</strong> {{ $direccionFalta }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 6px;" colspan="4"><strong>Imputados:</strong> {{ $imputadosDesc }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 6px;" colspan="4"><strong>Infracciones:</strong> {{ $infraccionesDesc }}</td>
        </tr>
        <tr>
            <td style="padding: 4px 6px;" colspan="4"><strong>Padrones:</strong> {{ $padronesDesc }}</td>
        </tr>
    </table>
</div>
