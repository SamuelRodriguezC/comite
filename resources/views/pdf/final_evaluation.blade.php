<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Formato de Evaluación Final</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: #fff;
            font-size: 13px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid black;
            padding: 4px 6px;
            vertical-align: top;
        }

        .no-border td,
        .no-border th {
            border: none;
        }

        .title {
            font-weight: bold;
            font-size: 14px;
        }

        .subtitle {
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .input-cell {
            color: gray;
        }

        .shaded {
            background-color: #f2f2f2;
        }

        .text-small {
            font-size: 12px
        }

        .text-center {
            text-align: center;
            vertical-align: middle;
        }

        .align-center {
            vertical-align: middle;
        }

        .table-no-bottom tr:last-child td {
            border-bottom: none;
        }

        ul {
            list-style-type: none;
        }

        .dash-list li::before {
            padding-left: 0;
            margin-left: 0;
            content: "- ";
        }
    </style>
</head>

<body class="p-8">

    <!----------------------- ENCABEZADO ----------------------- -->
    <table class="w-full">
        <tr>
            <td style="width:70px;" class="center">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}"
                    alt="Logo" class="mx-auto" style="width:50px;">
            </td>

            <!-- Nombre universidad -->
            <td style="text-align:center;">
                <div style="font-size:14px; font-weight:bold;">UNIVERSIDAD LIBRE</div>
                <div style="font-size:10px;">FAC. INGENIERÍA</div>
                <div style="font-size:10px;">CENTRO DE INVESTIGACIÓN</div>
            </td>
            <td style="text-align:center; vertical-align:middle; font-size:14px; font-weight:bold;">
                EVALUACIÓN OPCIONES CON COMPONENTE DE INVESTIGACIÓN
            </td>
        </tr>
    </table><br>

    <!-----------------------TITULO DEL PROYECTO ----------------------- -->
    <table class="mb-2 table-no-bottom">
        <tr>
            <td class="subtitle shaded">
                Título del Proyecto: {{ $data['project_name'] ?? 'Proyecto Sin Título' }}
            </td>
        </tr>
    </table>

    <!----------------------- INFORMACIÓN DE ESTUDIANTES ----------------------- -->
    <table class="mb-2 table-no-bottom">
        <tr>
            <td class="subtitle">1. Información de los Estudiantes</td>
        </tr>
        @foreach ($data['students'] as $index => $student)
            <tr>
                <td>{{ $index + 1 }}.1 Nombre: {{ $student['name'] ?? 'No Registrado' }}</td>
            </tr>
            <tr>
                <td>{{ $index + 1 }}.2 Código: {{ $student['code'] ?? 'Código no Disponible' }}</td>
            </tr>
        @endforeach
    </table>

    <!----------------------- ALTERNATIVA DE GRADO ----------------------- -->
    <table class="mb-2 table-no-bottom">
        <tr>
            <td class="subtitle shaded">2. Alternativa de trabajo de grado</td>
        </tr>
        <tr>
            <td>{{ $data['grade_option'] }}</td>
        </tr>
    </table>

    <!----------------------- JURADOS ----------------------- -->
    <table class="mb-2 table-no-bottom">
        <tr>
            <td colspan="2" class="subtitle shaded">3. Jurados del Proyecto</td>
        </tr>
        <tr>
            <td>4.1 Nombre del Jurado 1:<br><span class="input-cell">{{ $data['jury_1_name'] ?? 'Sin Jurado
                    Seleccionado' }}</span></td>
            <td>4.2 Nombre del Jurado 2:<br><span class="input-cell">{{ $data['jury_2_name'] ?? 'Sin Jurado
                    Seleccionado'}}</span></td>
        </tr>
    </table>

    <!----------------------- DIRECTOR ----------------------- -->
    <table class="mb-2 table-no-bottom">
        <tr>
            <td class="subtitle shaded">4. Director del Proyecto</td>
        </tr>
        <tr>
            <td>5.1 Nombre del docente o persona que dirige el proyecto de grado:<br>
                <span class="input-cell">{{ $data['advisor_name'] ?? 'Sin Director Seleccionado'}}</span>
            </td>
        </tr>
    </table>

    <!----------------------- ESCALA DE EVALUACIÓN ----------------------- -->
    <table class="mb-2 table-no-bottom">
        <tr>
            <td colspan="3" class="subtitle shaded">5. Escala de evaluación:</td>
        </tr>
        <tr>
            <td><b>5 – 4.5</b> Excelente nivel de calidad</td>
            <td><b>4.4 – 4.0</b> Buen nivel de calidad</td>
            <td><b>3.9 – 3.5</b> Aceptable nivel de calidad</td>
        </tr>
        <tr>
            <td><b>3.4 – 3.0</b> Insuficiente nivel de calidad</td>
            <td colspan="2"><b>Menos de 3.0</b> Bajo nivel de calidad</td>
        </tr>
        <tr>
            <td colspan="3" class="text-small">
                “Cada miembro del jurado deberá generar una nota final sobre el trabajo de grado.
                La evaluación final tendrá nota Aprobatoria cuando el resultado final sea de tres puntos seis (3.6)
                o superior en la escala de 1 a 5. Si existe diferencia en el concepto final asignado por los miembros
                del jurado sobre el trabajo de grado (uno aprobatorio y otro reprobando), se nombrará un tercer miembro
                para dirimir la situación, quien calificará de forma independiente y se computará con las notas de los
                anteriores docentes ya nombrados”
            </td>
        </tr>
    </table>

    <!----------------------- CATEGORÍAS DE EVALUACIÓN INFORME FINAL ----------------------- -->
    <table class="mb-2 table-no-bottom">
        <tr>
            <td class="subtitle shaded" colspan="3">6. Categorías de evaluación informe final</td>
        </tr>

        @foreach($data['final_report'] as $report)
        <tr>
            <td colspan="3">
                <b>7.{{ $loop->iteration }} {{ $report['name'] ?? 'Sin Nombre' }}.</b>
                Las consideraciones a tener en cuenta para evaluar este componente son: <br>
                <ul class="dash-list">
                    @foreach ($report['parameters'] as $parameter)
                    <li>{{ $parameter }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <span class="input-cell">{{ $report['text'] ?? 'Sin Observaciones'}}</span>
            </td>
        </tr>

        <tr>
            <td colspan="3">
                <b>Nota para este apartado (escala de 1 a 5):</b>
                <span class="input-cell">{{ $report['grade'] }}</span>
            </td>
        </tr>
        @endforeach

        <!----------------------- ASPECTOS COMPLEMENTARIOS ----------------------- -->
        <tr>
            <td colspan="3"><b>7.{{ count($data['final_report']) + 1 }} Aspectos complementarios</b></td>
        </tr>

        <tr>
            <td style="width: 60%;"></td>
            <td class="center" style="width: 10%;">Si</td>
            <td class="center" style="width: 10%;">No</td>
        </tr>

        <tr>
            <td style="width: 60%;">¿El documento viene acompañado de una carta de aprobación de la empresa?</td>
            <td class="center" style="width: 10%;">{{ !empty($data['company_approval']) && $data['company_approval'] ?
                'X' : '' }}</td>
            <td class="center" style="width: 10%;">{{ isset($data['company_approval']) && !$data['company_approval']
                ?'X' : '' }}</td>
        </tr>

        <tr>
            <td style="width: 60%;">¿Se ha verificado la ejecución del proyecto en la empresa?</td>
            <td class="center" style="width: 10%;">{{ !empty($data['company_verification']) &&
                $data['company_verification'] ? 'X' : '' }}</td>
            <td class="center" style="width: 10%;">{{ isset($data['company_verification']) &&
                !$data['company_verification'] ? 'X' : '' }}</td>
        </tr>
    </table>

    <!----------------------- EVALUACIÓN INFORME FINAL ----------------------- -->
    <table>
        <!-- Título de la sección -->
        <tr>
            <td class="subtitle shaded" colspan="4">7. Evaluación Informe Final</td>
        </tr>
        <tr>
            <th width="40%">Categoría</th>
            <th width="10%">Nota</th>
            <th width="10%">Ponderación</th>
            <th width="40%">Observación</th>
        </tr>
        <!-- Evaluaciones -->
        @foreach ( $data['final_report'] as $report)
        <tr>
            <td class="align-center">{{ $report['name'] ?? 'Sin Nombre'}}</td>
            <td class="text-center align-center">{{ $report['grade'] ?? ''}}</td>
            <td class="text-center align-center">{{ $report['weight'] ?? '' }}%</td>
            <td>{{ $report['text'] }}</td>
        </tr>
        @endforeach
        <!-- Nota Final -->
        <tr>
            <td>Nota Evaluación Informe Final</td>
            <td class="text-center align-center">{{ $data['final_report_grade'] ?? ''}}</td>
            <td colspan="2">Nota final con dos cifras significativas</td>
        </tr>
    </table><br>

    <!----------------------- EVALUACIÓN SUSTENTACIÓN ----------------------- -->
    <table>
        <!-- Título de la sección -->
        <tr>
            <td class="subtitle shaded" colspan="4">8. Evaluación Sustentación Proyecto 60%</td>
        </tr>
        <tr>
            <th width="40%">Categoría</th>
            <th width="10%">Nota</th>
            <th width="10%">Ponderación</th>
            <th width="40%">Observación</th>
        </tr>
        <!-- Evaluaciones -->
        @foreach ( $data['projects_support'] as $support)
        <tr>
            <td class="align-center">{{ $support['name'] ?? '' }}</td>
            <td class="text-center align-center">{{ $support['grade'] ?? '' }}</td>
            <td class="text-center align-center">{{ $support['weight'] ?? '' }}%</td>
            <td>{{ $support['text'] }}</td>
        </tr>
        @endforeach
        <!-- Nota Final -->
        <tr>
            <td>Nota Evaluación Informe Final</td>
            <td class="text-center align-center">{{ $data['projects_support_grade'] ?? '' }}</td>
            <td colspan="2">Nota final con dos cifras significativas</td>
        </tr>
    </table><br>


    <!----------------------- EVALUACIÓN FINAL ----------------------- -->
    <table>
        <tr>
            <td class="subtitle shaded" colspan="4">9. Evaluación Final Proyecto De Grado</td>
        </tr>
        <tr>
            <th width="40%">Categoría</th>
            <th width="10%">Nota</th>
            <th width="10%">Ponderación</th>
            <th width="40%" style="background-color: #d9d9d9;"></th>
        </tr>
        <tr>
            <td>Nota Evaluación Informe Final</td>
            <td class="text-center align-center">{{ $data['final_report_grade'] ?? '' }}</td>
            <td class="text-center align-center">40%</td>
            <td style="background-color: #d9d9d9;"></td>
        </tr>
        <tr>
            <td>Evaluación sustentación proyecto</td>
            <td class="text-center align-center">{{ $data['projects_support_grade'] ?? '' }}</td>
            <td class="text-center align-center">60%</td>
            <td style="background-color: #d9d9d9;"></td>
        </tr>
        <tr>
            <td>Nota final proyecto de grad</td>
            <td class="text-center align-center">{{ $data['final_grade'] ?? '' }}</td>
            <td colspan="2">Nota final con dos cifras significativas</td>
        </tr>
    </table><br>

    <!----------------------- FIRMAS ----------------------- -->
    <table style="width:100%; border-collapse: collapse; border: 1px solid black;">
        <tr>
            <!-- Jurado 1 -->
            <td style="width:50%; text-align:start; vertical-align:top; border: 1px solid black; padding:10px;">
                Nombre y Firma del Jurado:<br>
                {{ $data['jury_1_name'] ?? 'No Seleccionado'}}
                @if(!empty($data['jury_1_signature']) && file_exists(storage_path('app/private/'
                .$data['jury_1_signature'])))
                <br>
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/private/' . $data['jury_1_signature']))) }}"
                    alt="Firma" style="width: 130px; margin-top:5px;">
                <div style="border-top: 1px solid black; width:150px; margin:5px 0 0;"></div>
                @else
                <div style="border-top: 1px solid black; width:150px; margin:10px 0 0;"></div>
                @endif
            </td>

            <!-- Jurado 2 -->
            <td style="width:50%; text-align:start; vertical-align:top; border: 1px solid black; padding:10px;">
                Nombre y Firma del Jurado:<br>
                {{ $data['jury_2_name'] ?? 'No Seleccionado' }}
                @if(!empty($data['jury_2_signature']) && file_exists(storage_path('app/private/'
                .$data['jury_2_signature'])))
                <br>
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/private/' . $data['jury_2_signature']))) }}"
                    alt="Firma" style="width: 130px; margin-top:5px;">
                <div style="border-top: 1px solid black; width:150px; margin:5px 0 0;"></div>
                @else
                <div style="border-top: 1px solid black; width:150px; margin:10px 0 0;"></div>
                @endif
            </td>
        </tr>

        <!-- Evaluador-->
        <tr>
            <td colspan="2" style="text-align:center; vertical-align:top; border: 1px solid black; padding:10px;">
                Nombre y firma evaluado:<br>
                {{ $data['evaluator_name'] ?? 'No Seleccionado' }}
                @if(!empty($data['evaluator_signature']) && file_exists(storage_path('app/private/' .
                $data['evaluator_signature'])))
                <br>
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/private/' . $data['evaluator_signature']))) }}"
                    alt="Firma" style="width: 130px; margin-top:5px;">
                <div style="border-top: 1px solid black; width:150px; margin: 5px auto 0;"></div>
                @else
                <div style="border-top: 1px solid black; width:150px; margin: 5px auto 0;"></div>
                @endif
            </td>
        </tr>
    </table>
</body>
</html>
