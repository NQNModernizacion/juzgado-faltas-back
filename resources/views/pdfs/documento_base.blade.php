<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Documento Legal</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #000;
        }

        /* Reglas Críticas de Impresión para Chromium / Puppeteer */
        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            /* 1. Evitar que elementos estructurales se corten por la mitad horizontalmente */
            p, li, tr, blockquote {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            /* 2. Evitar que los títulos queden "huérfanos" al final de la hoja */
            h1, h2, h3, h4, h5 {
                break-after: avoid;
                page-break-after: avoid;
            }

            /* 3. Control del Bloque de Firmas */
            .bloque-firmas {
                break-inside: avoid;
                page-break-inside: avoid;
                margin-top: 80px; /* Distancia con el texto dinámico */
                display: flex;
                justify-content: space-around;
                text-align: center;
                page-break-before: auto;
            }

            .firma-box {
                width: 40%;
                border-top: 1px solid #000;
                padding-top: 10px;
            }
        }
        
        .contenido-dinamico {
            width: 100%;
            text-align: justify;
        }
    </style>
</head>
<body>
    <div class="contenido-dinamico">
        {!! $documento->contenido_html !!}
    </div>
</body>
</html>
