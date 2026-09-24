<style>
    .header-wrapper {
        width: 100%;
        box-sizing: border-box;
        padding: 0 20mm;
        font-family: Arial, sans-serif;
        -webkit-print-color-adjust: exact;
    }
    .header-container {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding-bottom: 6px;
        border-bottom: 1.5px solid #333;
    }
    .header-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .header-left img {
        width: 48px;
        height: auto;
    }
    .header-text {
        display: flex;
        flex-direction: column;
        line-height: 1.35;
        font-size: 9pt;
        color: #111;
    }
    .header-text strong {
        font-size: 10pt;
        letter-spacing: 0.5px;
    }
    .header-right {
        text-align: right;
        font-size: 9pt;
        line-height: 1.4;
        color: #111;
    }
    .header-right .expediente {
        font-weight: bold;
        font-size: 9.5pt;
    }
    .header-right .fecha {
        font-size: 8.5pt;
        color: #444;
        margin-top: 3px;
    }
</style>
<div class="header-wrapper">
    <div class="header-container">
        <div class="header-left">
            @if(!empty($sello_base64))
                <img src="{{ $sello_base64 }}" alt="Escudo">
            @endif
            <div class="header-text">
                <strong>TRIBUNAL MUNICIPAL Nº {{ $juzgado_nro ?? '1' }}</strong>
                <span>{{ $juzgado_direccion ?? 'MITRE Nº 461' }}</span>
                <span>CIUDAD DE {{ $ciudad ?? 'NEUQUEN' }} CAPITAL</span>
            </div>
        </div>
        <div class="header-right">
            <div class="expediente">Expediente/s: {{ $juzgado_nro ?? '1' }} - {{ $causa_anio ?? '' }} - {{ $causa_nro ?? '' }}</div>
            <div class="fecha">NEUQUÉN, {{ \Carbon\Carbon::now()->locale('es')->translatedFormat('d \d\e F \d\e\l Y') }}</div>
        </div>
    </div>
</div>
