<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resultado Formulario</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f7f7f7;
            margin: 0;
            padding: 20px;
            color: #000;
        }

        h1 {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 30px;
        }

        h2 {
            font-size: 1.6rem;
            margin-bottom: 15px;
            border-bottom: 3px solid #eee;
            padding-bottom: 6px;
        }

        .card {
            background: #fff;
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 25px;
            box-shadow: 0 8px 20px rgba(5, 5, 5, 0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.15);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill,minmax(250px,1fr));
            gap: 16px;
        }

        .campo {
            background: #fafafa;
            border-radius: 12px;
            padding: 12px;
            box-shadow: inset 0 1px 5px rgba(0,0,0,0.05);
        }

        .campo p {
            margin: 0;
        }

        .campo .label {
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 4px;
        }

        .campo .valor {
            font-size: 1rem;
            font-weight: 600;
            color: #000;
        }

        .criterio {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 8px;
        }

        .criterio span {
            font-size: 0.9rem;
        }

        .criterio .ok {
            background: #e6f6ec;
            color: #1d8a4d;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
        }

        .criterio .fail {
            background: #fde8e8;
            color: #c53030;
            padding: 4px 10px;
            border-radius: 12px;
            font-weight: 600;
        }

        .button-download {
            display: inline-block;
            padding: 14px 28px;
            font-size: 1rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(to right, #000, #111);
            border: none;
            border-radius: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            margin-top: 20px;
        }

        .button-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }

        footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.85rem;
            color: #777;
        }
    </style>
</head>
<body>

<h1>Datos enviados correctamente</h1>

<div class="card">
    <h2>Datos Generales</h2>
    <div class="grid">
        @foreach($datos as $campo => $valor)
            @if($campo !== "_token" && !str_starts_with($campo, 'criterio'))
                <div class="campo">
                    <p class="label">{{ str_replace('_', ' ', $campo) }}</p>
                    <p class="valor">{{ $valor }}</p>
                </div>
            @endif
        @endforeach
    </div>
</div>

<div class="card">
    <h2>Categorías de Evaluación</h2>
    @php
        $criterios = [
            "Articulación institucional: ¿La temática tiene relación con las líneas de investigación y ejes temáticos?",
            "Fundamentación: ¿La temática tiene relación con la formación en básica de ingeniería?",
            "Cuantificación del problema: ¿En el planteamiento del problema existe una suficiente cuantificación de este?",
            "Descripción del problema: ¿Se identifican causas, consecuencias, actores involucrados?",
            "Pertinencia del anteproyecto: ¿La propuesta cumple con los requisitos de grado?",
            "Formulación del problema: ¿El enunciado es comprensible?",
            "Justificación: ¿Se describe la utilidad de los resultados?",
            "Objetivos: ¿El objetivo general responde al problema?",
            "Definición del alcance: ¿Está correctamente delimitado?",
            "Antecedentes: ¿Existen referentes claros?",
            "Marco teórico y conceptual: ¿Son pertinentes?",
            "Metodología: ¿Está bien descrita?",
            "Presupuesto: ¿Se distinguen los recursos?",
            "Presentación: ¿Cumple normas y tiene buena redacción?"
        ];
    @endphp

    @foreach($criterios as $index => $texto)
        <div class="criterio">
            <span>{{ $texto }}</span>
            <span class="{{ ($datos["criterio$index"] ?? '') === 'Si' ? 'ok' : 'fail' }}">
                {{ $datos["criterio$index"] ?? '' }}
            </span>
        </div>
    @endforeach
</div>

<form action="{{ route('formulario.pdf') }}" method="POST" class="text-center">
    @csrf
    @foreach($datos as $campo => $valor)
      <input type="hidden" name="{{ $campo }}" value="{{ $valor }}">
    @endforeach
    <button type="submit" class="button-download">Descargar PDF</button>
</form>

<footer>
    © {{ date('Y') }} Universidad Libre · Sistema de Evaluación de Anteproyecto
</footer>

</body>
</html>
