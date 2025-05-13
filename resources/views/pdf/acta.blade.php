<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de opción de Grado</title>
    <style>
        body {
            font-family: 'DejaVu Sans';
            font-size: 12px;
            margin: 40px;
        }

        header {
            text-align: center;
            margin-bottom: 30px;
        }

        h1, h2, h3 {
            margin: 0;
        }

        .info {
            margin-bottom: 25px;
        }

        .info p {
            margin: 3px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px 4px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .observaciones {
            margin-top: 30px;
        }

        .firmas {
            margin-top: 60px;
        }

        .firma-block {
            display: inline-block;
            width: 30%;
            text-align: center;
            vertical-align: top;
        }

        .firma-linea {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }

        footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #777;
        }
    </style>
</head>
<body>

    <header>
        <table style="width: 100%; margin-bottom: 20px; border:none;">
            <tr>
                <td style="width: 100px; border: none;">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo" width="100">
                    <!--
                    <img src="{{ public_path('images/logo.png') }}" alt="Logo Universidad Libre" width="100">
                    -->
                </td>
                <td style="text-align: center; border: none;">
                    <h1 style="margin: 0; border:none;">UNIVERSIDAD LIBRE</h1>
                    <h2 style="margin: 0; border:none;">Facultad de Ingeniería</h2>
                    <h3 style="margin: 0; border:none;">Acta de Finalización de Opción de Grado</h3>
                </td>
            </tr>
        </table>
    </header>

    <section class="info">
        <p><strong>Número de acta:</strong> Acta-{{ $transaction->id }}</p>
        <p><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y') }}</p>
        <!--
        <p><strong>Opción de grado:</strong> { { $transaction->option->option ?? 'No especificada' }}</p>
        -->
    </section>

    <section>
        <p style="text-align: justify; font-size: 14px; line-height: 1.5;">
            Se certifica que
            @if ($estudiantes->count() === 1)
                el(la) estudiante <strong>{{ $estudiantes[0]->profile->full_name }}</strong>, identificado(a) con el número de documento <strong>{{ $estudiantes[0]->profile->document_number }}</strong>,
                matriculado(a) en la carrera de <strong>{{ $estudiantes[0]->courses->course ?? 'No asignada' }}</strong>, ha
            @else
                los(as) estudiantes
                @foreach ($estudiantes as $index => $estudiante)
                    <strong>{{ $estudiante->profile->full_name }}</strong>, identificado(a) con <strong>{{ $estudiante->profile->document->type }}</strong> número <strong>{{ $estudiante->profile->document_number }}</strong>@if ($index < $estudiantes->count() - 2), @elseif ($index == $estudiantes->count() - 2) y @else. @endif
                @endforeach
                matriculados(as) en la(s) carrera(s):
                @php
                    $carreras = $estudiantes->pluck('courses.course')->unique()->implode(', ');
                @endphp
                <strong>{{ $carreras }}</strong>, han
            @endif
            finalizado satisfactoriamente la modalidad de opción de grado titulada <strong>{{ $transaction->option->option ?? 'No especificada' }}</strong>,
            cumpliendo con los requisitos académicos establecidos por la institución.
        </p>
    </section>

    <section class="observaciones">
        <p><strong>Observaciones:</strong></p>
        <p style="border: 1px solid #000; height: 80px; padding: 10px;">
            {{-- Puedes incluir dinámicamente observaciones aquí si las tienes --}}
        </p>
    </section>

    <section class="firmas">
        <div class="firma-block">
            <div class="firma-linea"></div>
            <p>Coordinador</p>
        </div>
        <!--
        <div class="firma-block">
            <div class="firma-linea"></div>
            <p>Tutor</p>
        </div>
        <div class="firma-block">
            <div class="firma-linea"></div>
            <p>Estudiante</p>
        </div>
        -->
    </section>

    <footer>
        Documento generado automáticamente por el sistema de gestión de opciones de grado.
    </footer>
</body>
</html>
