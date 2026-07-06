<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Carátula de Expediente - {{ $acta->numero_acta }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #000;
            background-color: #fff;
        }

        .caratula-page {
            width: 100%;
            box-sizing: border-box;
            padding: 40px;
            text-align: center;
        }

        .header {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        /* Logotipo o contenedor de escudo */
        .escudo-container {
            margin-bottom: 15px;
        }
        
        .escudo-container svg {
            width: 80px;
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .tribunal-title {
            font-size: 13pt;
            margin: 0 0 10px 0;
            font-weight: normal;
        }

        .juzgado-title {
            font-size: 20pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }

        .secretaria-title {
            font-size: 15pt;
            font-weight: normal;
            margin: 0 0 15px 0;
        }

        /* Caja de observaciones idéntica al ejemplo */
        .observaciones-box {
            border: 1px solid #000;
            margin: 15px auto;
            width: 80%;
            box-sizing: border-box;
        }

        .observaciones-title {
            border-bottom: 1px solid #000;
            font-size: 9pt;
            padding: 3px 0;
            text-transform: uppercase;
            background-color: #f5f5f5;
            letter-spacing: 0.5px;
        }

        .observaciones-content {
            height: 70px;
            padding: 8px;
            font-size: 10.5pt;
            text-align: left;
            word-wrap: break-word;
        }

        /* Título central grande de la oficina */
        .oficina-resumida-box {
            margin-top: 50px;
            margin-bottom: 30px;
        }

        .oficina-resumida-text {
            font-size: 34pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        /* Expediente y Año */
        .expediente-box {
            margin-bottom: 40px;
        }

        .expediente-text {
            font-size: 24pt;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .anio-text {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
        }

        /* Sección de Autos (Imputados/Infractores) */
        .autos-box {
            margin: 30px auto;
            width: 85%;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.4;
            text-align: center;
        }

        /* Falta/s Imputada/s */
        .faltas-box {
            margin-top: 40px;
            font-size: 12pt;
            font-weight: normal;
        }

        .faltas-label {
            display: inline-block;
        }

        .faltas-value {
            font-weight: bold;
        }

        /* Tabla de Pie de Página */
        .bottom-table {
            width: 80%;
            margin: 60px auto 10px auto;
            border-collapse: collapse;
            border: 1px solid #000;
        }

        .bottom-table td {
            border: 1px solid #000;
            padding: 12px 15px;
            font-size: 11.5pt;
            vertical-align: middle;
        }

        .left-cell {
            width: 60%;
            text-align: left;
            font-weight: normal;
        }

        .right-cell {
            width: 40%;
            text-align: left;
            font-weight: normal;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<div class="caratula-page">
    <div class="header">
        <div class="escudo-container">
            @include('pdfs.partials.logo_tribunal')
        </div>
        <div class="tribunal-title">Tribunal Municipal de Faltas</div>
        <div class="juzgado-title">JUZGADO Nº {{ $acta->juzgado->numero_juzgado ?? '2' }}</div>
        <div class="secretaria-title">Secretaría Nº {{ substr($acta->secretaria->codigo ?? '11', 1, 1) ?: '1' }}</div>
    </div>

    <!-- Recuadro Observaciones -->
    <div class="observaciones-box">
        <div class="observaciones-title">Observaciones</div>
        <div class="observaciones-content">
            {{ $acta->observacion }}
        </div>
    </div>

    <!-- Título Principal de la Oficina (FM1/FM2 -> FOTOMULTA) -->
    <div class="oficina-resumida-box">
        <div class="oficina-resumida-text">{{ $oficinaResumida }}</div>
    </div>

    <!-- Expediente y Año -->
    <div class="expediente-box">
        <div class="expediente-text">Expte. Nº <span style="font-size: 28pt;">{{ $acta->numero_causa }}</span></div>
        <div class="anio-text">
            {{ $acta->fecha_labrada ? \Carbon\Carbon::parse($acta->fecha_labrada)->format('Y') : \Carbon\Carbon::now()->format('Y') }}
        </div>
    </div>

    <!-- Autos / Imputados (Tamaño adaptativo) -->
    <div class="autos-box" style="font-size: {{ strlen($autosText) > 60 ? '14pt' : (strlen($autosText) > 30 ? '17pt' : '20pt') }};">
        AUTOS: {{ $autosText ?: 'SIN IMPUTADOS' }}
    </div>

    <!-- Faltas Imputadas (Artículos) -->
    <div class="faltas-box">
        <span class="faltas-label">Falta/s Imputada/s:</span>
        <span class="faltas-value">{{ $faltasText ?: 'Ninguna' }}</span>
    </div>

    <!-- Tabla Final -->
    <table class="bottom-table">
        <tr>
            <td class="left-cell">Acta de Infracción Nº: &nbsp;<strong>{{ $acta->numero_acta }}</strong></td>
            <td class="right-cell">Fecha: &nbsp;<strong>{{ $acta->fecha_labrada ? \Carbon\Carbon::parse($acta->fecha_labrada)->format('d/m/Y') : '' }}</strong></td>
        </tr>
    </table>
</div>

</body>
</html>
