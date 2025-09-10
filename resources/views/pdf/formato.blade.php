<!DOCTYPE html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Evaluación Anteproyecto de Grado</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2, h3 { text-align: center; margin: 0; padding: 2px; }
        .section { margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table, th, td { border: 1px solid #000; }
        th, td { padding: 6px; vertical-align: top; }
        .no-border { border: none !important; }
        .footer-text { margin-top: 20px; font-size: 11px; text-align: justify; }
        body {
         font-family: 'DejaVuSans', sans-serif;
         font-size: 12px;
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <table style="width:100%; border:1px solid #000; border-collapse:collapse; text-align:center;">
        <tr>
            <!-- Logo -->
            <td style="width:20%; border:1px solid #000; text-align:center;">
                <img src="{{ public_path('images/logoUniversidad.png') }}" alt="Logo Universidad" style="max-width:90px;">
            </td>

            <!-- Texto institucional -->
            <td style="width:40%; border:1px solid #000; text-align:center; vertical-align:middle;">
                <strong>UNIVERSIDAD LIBRE</strong><br>
                <span style="font-size:12px;">FAC. INGENIERÍA</span><br>
                <span style="font-size:12px;">COMITÉ DE OPCIONES DE GRADO</span><br>
                <span style="font-size:12px;">CON COMPONENTE DE INVESTIGACIÓN</span>
            </td>

            <!-- Título -->
            <td style="width:40%; border:1px solid #000; text-align:center; vertical-align:middle;">
                <span style="font-size:14px; font-weight:bold;">EVALUACIÓN ANTEPROYECTO DE GRADO</span>
            </td>
        </tr>
    </table>

    <!-- Datos Generales -->
    <table>
        <tr>
            <th style="width:30%;">1. Título del Anteproyecto</th>
            <td>{{ $datos['titulo'] ?? '' }}</td>
        </tr>
        <tr>
            <th>2.1 Nombre estudiante 1</th>
            <td>{{ $datos['nombre1'] ?? '' }} - Código: {{ $datos['codigo1'] ?? '' }}</td>
        </tr>
        <tr>
            <th>2.2 Nombre estudiante 2</th>
            <td>{{ $datos['nombre2'] ?? '' }} - Código: {{ $datos['codigo2'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Empresa u organización</th>
            <td>{{ $datos['empresa'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Dirección</th>
            <td>{{ $datos['direccion'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Contacto</th>
            <td>{{ $datos['contacto'] ?? '' }} - Cargo: {{ $datos['cargo'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Teléfono</th>
            <td>{{ $datos['telefono'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Correo</th>
            <td>{{ $datos['correo'] ?? '' }}</td>
        </tr>
    </table>

    <!-- Categorías de Evaluación -->
    <div class="section">
        <table>
            <thead>
                <tr>
                    <th style="width:80%;">Categoría de Evaluación</th>
                    <th style="width:20%;">Respuesta</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $criterios = [
                        "Articulación institucional: ¿La temática tiene relación con las líneas de investigación y ejes temáticos?",
                        "Fundamentación: ¿La temática tiene relación con la formación en básica de ingeniería?",
                        "Cuantificación del problema: ¿En el planteamiento del problema existe una suficiente cuantificación de este?",
                        "Descripción del problema: ¿Se identifican causas, consecuencias, actores involucrados?",
                        "Pertinencia del anteproyecto: ¿La propuesta cumple con los requisitos de grado?",
                        "Formulación del problema: ¿El enunciado es comprensible?",
                        "Coherencia entre la pregunta y la descripción del problema",
                        "Relación entre causas y efectos claramente manifiesta",
                        "Justificación: ¿Se describe la utilidad de los resultados?",
                        "Objetivos: ¿El objetivo general responde al problema?",
                        "Objetivos específicos coherentes con el objetivo general",
                        "Definición del alcance: ¿Está correctamente delimitado?",
                        "Clarificación de poblaciones y muestras",
                        "Definición de procesos de trabajo",
                        "Ubicación geográfica del objeto de estudio",
                        "Nivel de implementación de soluciones",
                        "Planificación del tiempo de duración del proyecto",
                        "Impacto social, ambiental y económico del proyecto",
                        "Antecedentes documentados locales, nacionales e internacionales",
                        "Conclusiones analíticas sobre los antecedentes",
                        "Marco teórico coherente con los objetivos",
                        "Marco conceptual comprensible",
                        "Conceptos bajo la autoría del estudiante",
                        "Metodología completa y precisa",
                        "Uso de metodologías propias de la ingeniería",
                        "Cronograma adecuado según metodología",
                        "Criterios del marco lógico aplicados",
                        "Presupuesto: recursos diferenciados",
                        "Presentación: plantilla de la facultad",
                        "Presentación: normas IEEE",
                        "Presentación: redacción y ortografía"
                    ];
                @endphp

                @foreach ($criterios as $index => $texto)
                    <tr>
                        <td>{{ $texto }}</td>
                        <td>{{ $datos["criterio$index"] ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Concepto Final -->
    <div class="section">
        <table>
            <tr>
                <th style="width:30%;">Concepto Final del Jurado</th>
                <td>{{ $datos['concepto'] ?? '' }}</td>
            </tr>
            <tr>
                <th>Nombre y firma del Jurado</th>
                <td>{{ $datos['firma'] ?? '' }}</td>
            </tr>
        </table>
    </div>

    <!-- Footer reglamentario -->
    <div class="footer-text">
        “El jurado tendrá un plazo máximo de quince (15) días hábiles para hacer lectura completa del mismo,
        y diligenciar el formato de concepto de evaluación definido por el comité del respectivo programa.
        El estudiante tendrá un mes de plazo para hacer las correcciones y el director enviará de nuevo por correo institucional
        al jurado, quien tendrá quince (15) días hábiles para evaluarlo nuevamente y emitir el concepto respectivo.
        El estudiante tiene derecho a presentar una (1) corrección al anteproyecto.”.
        <br><br>
        <strong>Reglamento de opciones de grado, 2015.</strong>
    </div>
</body>
</html>
