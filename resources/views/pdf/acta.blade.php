<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acta de Grado</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
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
        <h1>UNIVERSIDAD LIBRE</h1>
        <h2>Facultad de Ingeniería</h2>
        <h3>Acta de Opción de Grado</h3>
    </header>

    <section class="info">
        <p><strong>Número de acta:</strong> ACTA-{{ $transaction->id }}</p>
        <p><strong>Fecha de generación:</strong> {{ now()->format('d/m/Y') }}</p>
        <p><strong>Opción de grado:</strong> {{ $transaction->option->name ?? 'No especificada' }}</p>
    </section>

    <section>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre completo</th>
                    <th>Documento</th>
                    <th>Curso</th>
                    <th>Rol</th>
                </tr>
            </thead>
            <tbody>
                @forelse($detalles as $index => $detalle)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $detalle->profile->full_name }}</td>
                        <td>{{ $detalle->profile->document_number }}</td>
                        <td>{{ $detalle->course->course ?? 'No asignado' }}</td>
                        <td>{{ ucfirst($detalle->role->name) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No hay estudiantes asignados a esta transacción.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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
        <div class="firma-block">
            <div class="firma-linea"></div>
            <p>Tutor</p>
        </div>
        <div class="firma-block">
            <div class="firma-linea"></div>
            <p>Estudiante</p>
        </div>
    </section>

    <footer>
        Documento generado automáticamente por el sistema de gestión de opciones de grado.
    </footer>

</body>
</html>
