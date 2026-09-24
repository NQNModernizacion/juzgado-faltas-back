@php
    $movimientos = $acta->movimientos->sortBy('fecha_movimiento');
@endphp

<div style="margin-bottom: 20px;">
    <h4 style="margin-bottom: 6px; font-size: 11pt; text-transform: uppercase; border-bottom: 1px solid #ccc; padding-bottom: 4px;">
        Historial Cronológico de Movimientos
    </h4>
    <table style="width: 100%; border-collapse: collapse; font-size: 9.5pt; text-align: left;">
        <tbody>
            <tr style="background-color: #f2f2f2;">
                <td style="border: 1px solid #ccc; padding: 6px; width: 120px; font-weight: bold;">Fecha</td>
                <td style="border: 1px solid #ccc; padding: 6px; font-weight: bold;">Origen</td>
                <td style="border: 1px solid #ccc; padding: 6px; font-weight: bold;">Destino</td>
                <td style="border: 1px solid #ccc; padding: 6px; width: 60px; text-align: center; font-weight: bold;">Fojas</td>
                <td style="border: 1px solid #ccc; padding: 6px; font-weight: bold;">Motivo / Observación</td>
            </tr>
            @forelse($movimientos as $mov)
                <tr>
                    <td style="border: 1px solid #ccc; padding: 5px 6px;">
                        {{ $mov->fecha_movimiento ? \Carbon\Carbon::parse($mov->fecha_movimiento)->format('d/m/Y H:i') : '-' }}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 5px 6px;">
                        {{ $mov->oficinaOrigen->nombre ?? $mov->oficinaOrigen->descripcion ?? '-' }}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 5px 6px;">
                        {{ $mov->oficinaDestino->nombre ?? $mov->oficinaDestino->descripcion ?? '-' }}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 5px 6px; text-align: center;">
                        {{ $mov->fojas ?? '-' }}
                    </td>
                    <td style="border: 1px solid #ccc; padding: 5px 6px;">
                        {{ $mov->motivo ?? '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="border: 1px solid #ccc; padding: 8px; text-align: center; color: #666;">
                        No registra movimientos.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
