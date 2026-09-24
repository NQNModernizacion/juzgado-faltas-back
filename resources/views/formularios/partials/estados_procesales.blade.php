@php
    $estados = $acta->estadosProcesales->sortBy(function ($ep) {
        return $ep->pivot->fecha ?? $ep->pivot->created_at;
    });
@endphp

<div style="margin-bottom: 20px;">
    <h4 style="margin-bottom: 6px; font-size: 11pt; text-transform: uppercase; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
        Historial Cronológico de Estados Procesales
    </h4>
    <table style="width: 100%; border-collapse: collapse; font-size: 9.5pt; text-align: left;">
        <tbody>
            <tr style="background-color: #f2f2f2;">
                <td style="border: 1px solid #ccc; padding: 6px; width: 130px; font-weight: bold;">Fecha</td>
                <td style="border: 1px solid #ccc; padding: 6px; width: 220px; font-weight: bold;">Estado Procesal</td>
                <td style="border: 1px solid #ccc; padding: 6px; font-weight: bold;">Observaciones</td>
            </tr>
            @forelse($estados as $ep)
                @php
                    $fechaRaw = $ep->pivot->fecha ?? $ep->pivot->created_at;
                    $fecha = $fechaRaw ? \Carbon\Carbon::parse($fechaRaw)->format('d/m/Y H:i') : '-';
                @endphp
                <tr>
                    <td style="border: 1px solid #ccc; padding: 5px 6px;">{{ $fecha }}</td>
                    <td style="border: 1px solid #ccc; padding: 5px 6px;"><strong>{{ $ep->nombre ?? '-' }}</strong></td>
                    <td style="border: 1px solid #ccc; padding: 5px 6px;">{{ $ep->pivot->observacion ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="border: 1px solid #ccc; padding: 8px; text-align: center; color: #666;">
                        No registra estados procesales previos.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
