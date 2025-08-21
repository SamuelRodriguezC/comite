<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Certificación</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 40px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        .no-border {
            border: none !important;
        }
        .header-table td {
            font-size: 11px;
            vertical-align: top;
        }
        .header-logo {
            width: 90px;
            text-align: center;
        }
        .header-center {
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            padding: 2px;
        }
        .header-right {
            text-align: center;
            font-size: 10px;
        }
        .center-text {
            text-align: center;
        }
        h2 {
            margin: 25px 0 10px 0;
            text-align: center;
        }
        .signature {
            margin-top: 70px;
            text-align: start;
        }
        .signature img {
            height: 80px;
        }
        .bold {
            font-weight: bold;
        }
        .content {
            margin-top: 20px;
            text-align: justify;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <table class="header-table">
        <tr>
            <td class="header-logo">
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logo.png'))) }}" alt="Logo" width="80">
            </td>
            <td class="header-center">
                FORMATO CERTIFICACIÓN EN FORMACIÓN DE RECURSO HUMANO EN CTel_Dirección Trabajos de Grado_Pregrado
            </td>
            <td class="header-right">
                <strong>ST-INV-02-P-06-F16</strong><br>
                Versión 1<br>
                26/06/2024
            </td>
        </tr>
    </table>

    {{-- TÍTULO UNIVERSIDAD --}}
    <h2>UNIVERSIDAD LIBRE</h2>

    <p class="center-text" style="text-transform: uppercase;">
        LA DIRECCIÓN DE CENTRO DE INVESTIGACIÓN DE LA FACULTAD DE INGENIERÍA SECCIONAL BOGOTÁ D.C.
    </p>

    <div class="content">
        <p><strong>Hace constar que:</strong></p>

        <p>
            Los(as) estudiante(s) mencionado(s) a continuación han finalizado satisfactoriamente la modalidad de opción de
            grado titulada <strong>{{ $grade_option }}</strong>, cumpliendo con los requisitos académicos
            establecidos por la institución.
        </p>
    </div>

    {{-- TABLA ESTUDIANTES --}}
    <table style="margin-top: 20px;">
        <thead>
            <tr>
                <th>Nombre Completo</th>
                <th>Tipo de Documento</th>
                <th>Número de Documento</th>
                <th>Nivel Universitario</th>
                <th>Programa</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{ $student['fullname'] ?? 'No Encontrado' }}</td>
                <td>{{ $student['document_type'] ?? 'Sin Tipo de Documento' }}</td>
                <td>{{ $student['document_number'] ?? 'No Encontrado' }}</td>
                <td>{{ $student['level'] ? \App\Enums\Level::from($student['level'])->getLabel() : '' }}</td>
                <td>{{ $student['course'] ?? 'No Encontrado' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 30px;">
        Se firma en <strong>{{ $city }}</strong> a los <strong>{{ \Carbon\Carbon::now()->format('d') }}</strong> días del mes de
        <strong>{{ \Carbon\Carbon::now()->locale('es')->isoFormat('MMMM') }}</strong> del <strong>{{ \Carbon\Carbon::now()->year }}</strong>.
    </p>

    {{-- FIRMA --}}
    <div class="signature">
        @if(!empty($signatory['signature']) && file_exists(storage_path('app/public/' . $signatory['signature'])))
            <img
                src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $signatory['signature']))) }}"
                alt="Firma">
        @endif
        <p>
            {{ $signatory['fullname'] ?? 'Nombre del Director' }}<br>
            Director(a) Centro de Investigación<br>
            Facultad de {{ $signatory['faculty'] ?? '' }}<br>
            {{$signatory['seccional'] ?? '' }}<br>
            Universidad Libre
        </p>
    </div>

</body>
</html>
