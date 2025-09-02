<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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


    <table>
        <tr>
            <th style="width:30%;"> 1. Título del Anteproyecto</th>
            <td>{{ $datos['titulo'] ?? '' }}</td>
        </tr>
        <tr>
            <th>2.1 Nombre estudiante 1</th>
            <td>{{ $datos['nombre1'] ?? '' }} - Código: {{ $datos['codigo1'] ?? '' }}</td>
        </tr>
        <tr>
            <th>2.3 Nombre estudiante 2</th>
            <td>{{ $datos['nombre2'] ?? '' }} - Código: {{ $datos['codigo2'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Nombre de la empresa(s) u organizacion(es) en donde se desarrollara el proyecto: </th>
            <td>{{ $datos['empresa'] ?? '' }}</td>
        </tr>
        <tr>
            <th>Dirección de la empresa u organizacion en donde se desarrollara el proyecto: </th>
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

    <div class="section">
        <table>
            <thead>
                <tr>
                    <th style="width:80%;">4. Categoría de Evaluación sobre el ante proyecto</th>
                    <th style="width:20%;">Respuesta</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $criterios = [
                                "4.1 Articulación institucional: ¿La temática tiene relación con las líneas de investigación y ejes temáticos?",
                                "4.2 Fundamentación: ¿La temática tiene relación con la formación en básica de ingeniería(ingenieria ambiental, ingenieria de industrial, ingenieria de sistemas, ingenieria mecanica, ¿ingenieria en Ciencia de Datos)?",
                                "4.3 Cuantificación del problema: ¿En el planteamiento del problema existe una suficiente cuantificación de este?",
                                "4.4 Descripción del problema: ¿La descripcion del problema identifica causas, consecuencias, actores involucrados, dimensiones de analisis, y deja total claridad sobre su naturaleza?",
                                "4.5 Pertinencia del anteproyecto: ¿La propuesta es suficiente para cumplir el requisito exigido como trabajo de grado para optar al titulo profesional?",
                                "4.6 Formulación del problema: ¿El enunciado del problema es comprensible?",
                                " 4.7 Formulación del problema: ¿Existe coherencia entre la formulacion de la pregunta y la descripcion del problema?",
                                "4.8 Formulación del problema: ¿Existe una relacion entre causas y efectos manifestada claramente en la pregunta problema?",
                                "4.9 Justificación: ¿Se describe la utilidad que tendran los resultados del proyecto (tecnico, economico, social, ambientalm donde se justifique)?",
                                "4.10 Plantamiento de Objetivos: ¿El objetivo general responde a la formulacion del problema?",
                                "4.11 Plantamiento de Objetivos: ¿Los objetivos especificos son coherentes con el objetivo general?",
                                "4.12 Definición del alcance: ¿Las tematicas estan correctamente delimitadas?",
                                "4.13 Definición del alcance: ¿Se han clarificado las caracteristicas de poblaciones y muestras a estudiar?",
                                "4.14 Definición del alcance: ¿Se han definido los procesos en los que se va a trabajar?",
                                "4.15 Definición del alcance: ¿Se definio la ubicacion geografica del objeto de estudio?",
                                "4.16 Definición del alcance: ¿se han establecido el nivel de implementacion de las soluciones a obtener?",
                                "4.17 Definición del alcance: ¿se ha planificado el tiempo que va a durar el proyecto?",
                                "4.18 Definición del alcance: ¿se presenta el impacto social, ambiental y economico del proyecto?",
                                "4.19 Antecedentes: ¿Existen referentes locales, nacionales e internacionales documentados?",
                                "4.20 Antecedentes: ¿Existen conclusiones analiticas sobre estos antecedentes que puedan ser considerados como guias para el marco teorico?",
                                "4.21 Marco teórico: ¿Existe una relacion directa entre los temas tratados con los objetivos especificos de manera que se responda a ellos de manera suficiente y coherente?",
                                "4.22 Marco conceptual: ¿Los contenidos permiten comprender la forma como se entiende cada uno en el contexto del proyecto?",
                                "4.23 Marco conceptual: ¿Los conceptos incluidos han presentados bajo la propia autoria del estudiante?",
                                "4.24 Metodología: ¿Se describe de forma completa y precisa las actividades y procedimientos necesarios para dar respuesta a los objetivos propuestos?",
                                "4.25 Metodología: ¿Se propone el uso de metodologias propias de la ingenieria?",
                                "4.26 Metodología: ¿El cronograma esta bien planteado de acuerdo con la metodologia planteada?",
                                "4.27 Metodología: ¿En caso de haber formulado el proyecto con marco logico, ¿se evidencian los criterios de la logica vertical horizontal para el marco logico formulado?",
                                "4.28 Presupuesto: ¿Se distuguen los recursos requeridos de personal, materiales y equipos y gastos generales?",
                                "4.29 Presentación: ¿El documento esta escrito de acuerdo con la plantilla sugerida por la facultad de ingenieria?",
                                "4.30 Presentación: ¿El sistema de referenciacion utilizado con el documento esta acorde con la norma IEEE?",
                                "4.31 Presentación: ¿La redaccion del documento es adecuada y la ortografia es correcta?",
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

    <div class="section">
        <table>
            <tr>
                <th style="width:30%;"> 5. Concepto Final del Jurado</th>
                <td>{{ $datos['concepto'] ?? '' }}</td>
            </tr>
            <tr>
                <th>Nombre y firma del Jurado</th>
                <td>{{ $datos['firma'] ?? '' }}</td>
            </tr>
        </table>
    </div>

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
