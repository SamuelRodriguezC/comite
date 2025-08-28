<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Certificado Asesor</title>
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
<body class="m-12">

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

    {{-- UNIVERSIDAD --}}
    <h2>UNIVERSIDAD LIBRE</h2>

    <p class="center-text" style="text-transform: uppercase;">
        LA DIRECCIÓN DE CENTRO DE INVESTIGACIÓN DE LA FACULTAD DE INGENIERÍA SECCIONAL BOGOTÁ D.C.
    </p>

    <div class="text-center content">
        <p><strong>Hace constar que:</strong></p>

        <p>
            El (La) <strong>{{ $academic_title ? \App\Enums\AcademicTitle::from($academic_title)->getlabel() : '' }}</strong>
            <strong>{{ $advisor->full_name ?? 'Nombres y Apellidos' }}</strong>,
            identificado(a) con <strong>{{ $advisor->document->type ?? 'Tipo Documento' }}:
            {{ $advisor->document_number ?? 'Número Documento' }}</strong>,
            dirigió a cabalidad y llevó a buen término el siguiente trabajo de grado de pregrado
            del programa de <strong>{{ $advisor_course ?? 'Nombre del Programa' }}</strong>
            de la Facultad de Ingeniería.
        </p>
    </div>


    {{-- TABLA TRABAJO DE GRADO --}}
    <table style="margin-top: 20px;">
        <thead>
            <tr class="bg-gray-100">
                <th>Título del trabajo de grado</th>
                <th>Autor(es)</th>
                <th>Identificación Autor(es)</th>
                <th>Fecha de sustentación</th>
                <th>Rol</th>
                <th>Distinción</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $thesis_title }}</td>
                        <td class="px-2 py-1 border">
                            @foreach ($students as $student)
                                {{ $student->full_name }}
                                {{-- Si hay mas de un estudiante poner un separador --}}
                                @if (!$loop->last)
                                    -
                                @endif
                            @endforeach
                        </td>
                        <td class="px-2 py-1 border">
                            @foreach ($students as $student)
                                {{ $student->document->type }}: {{ $student->document_number }}
                                {{-- Si hay mas de un estudiante poner un separador --}}
                                @if (!$loop->last)
                                    -
                                @endif
                            @endforeach
                        </td>
                <td>{{ $defense_date->format('d/m/Y') }}</td>
                <td>{{ $advisor_role }}</td>
                <td>{{ $distinction ?? '' }}</td>
            </tr>
        </tbody>
    </table>

    {{-- FECHA --}}
    <p style="margin-top: 30px;">
        Se firma en <strong>{{ $city ?? 'Ciudad' }}</strong> a los
        <strong>{{ $date->format('d') }}</strong> días del mes de
        <strong>{{ $date->locale('es')->isoFormat('MMMM') }}</strong> del
        <strong>{{ $date->year }}</strong>.
    </p>

    {{-- FIRMA --}}
    <div class="signature">
        @if(!empty($signatory['signature']) && file_exists(storage_path('app/private/' . $signatory['signature'])))
            <img
                src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/private/' . $signatory['signature']))) }}"
                alt="Firma">
        @endif
        <p class="mt-2">
            {{ $signatory['fullname'] ?? 'Nombres y Apellidos' }}<br>
            Director(a) Centro de Investigación<br>
            Facultad de {{ $signatory['faculty'] ?? '' }}<br>
            {{ $signatory['seccional'] ?? '' }}<br>
            Universidad Libre
        </p>
    </div>

</body>
</html>
