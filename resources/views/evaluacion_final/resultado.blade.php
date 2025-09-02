<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Acta de Evaluación Final</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Inter', sans-serif;
        background: #ffffffff;
        margin: 0;
        padding: 20px;
        color: #000000ff;
    }

    h1 {
        text-align: center;
        font-size: 2.8rem;
        font-weight: 700;
        color: #000000ff;
        margin-bottom: 40px;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.05);
    }

    h2 {
        font-size: 1.7rem;
        margin-bottom: 20px;
        color: #141414ff;
        border-bottom: 3px solid #ffffffff;
        padding-bottom: 8px;
    }

    h3 {
        font-size: 1.3rem;
        color: #0c0c0cff;
        margin-bottom: 10px;
    }

    .card {
        background: #ffffffff;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 25px;
        box-shadow: 0 8px 20px rgba(5, 5, 5, 0.1);
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(12, 12, 12, 0.2);
    }

    .scrollable-textarea {
        max-height: 7rem;
        overflow-y: auto;
        padding: 12px;
        background: linear-gradient(180deg, #ffffffff 0%, #ffffffff 100%);
        border-radius: 12px;
        box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.08);
        font-size: 0.95rem;
        color: #000000ff;
        transition: all 0.3s ease;
    }

    .scrollable-textarea:hover {
        background: linear-gradient(180deg, #ffffffff 0%, #ffffffff 100%);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        font-size: 0.95rem;
    }

    th, td {
        padding: 10px 12px;
        border: 1px solid #ffffffff;
    }

    th {
        background: linear-gradient(90deg, #000000ff, #000000ff);
        color: white;
        font-weight: 600;
        text-align: left;
    }

    .flex-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .flex-item {
        flex: 1 1 45%;
    }

    .button-download {
        display: inline-block;
        padding: 14px 28px;
        font-size: 1rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(to right, #000000ff, #000000ff);
        border: none;
        border-radius: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        margin-top: 20px;
    }

    .button-download:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        background: linear-gradient(to right, #000000ff, #000000ff);
    }

    @media print {
        body { background: white; }
        .card { box-shadow: none; }
    }
</style>
</head>
<body>

<h1>Vista previa del Acta de Evaluación Final</h1>

{{-- Datos Generales --}}
<div class="card">
    <h2>Datos Generales</h2>
    <div class="flex-container">
        <div class="flex-item"><strong>Título:</strong> {{ $datos['titulo'] ?? 'N/A' }}</div>
        <div class="flex-item"><strong>Estudiante 1:</strong> {{ $datos['nombre1'] ?? 'N/A' }} - Código: {{ $datos['codigo1'] ?? 'N/A' }}</div>
        <div class="flex-item"><strong>Estudiante 2:</strong> {{ $datos['nombre2'] ?? 'N/A' }} - Código: {{ $datos['codigo2'] ?? 'N/A' }}</div>
        <div class="flex-item"><strong>Alternativa de grado:</strong> {{ $datos['alternativa'] ?? 'N/A' }}</div>
        <div class="flex-item"><strong>Director:</strong> {{ $datos['director'] ?? 'N/A' }}</div>
        <div class="flex-item"><strong>Jurados:</strong> 1. {{ $datos['jurado1'] ?? 'N/A' }}<br>2. {{ $datos['jurado2'] ?? 'N/A' }}</div>
    </div>
</div>

{{-- Categorías de Evaluación --}}
@php
    $categorias = [
        '7.1 Desarrollo Metodológico' => ['obs' => 'desarrollo_obs', 'nota' => 'desarrollo_nota', 'criterios' => [
            'Coherencia entre el problema de investigación y el diseño.',
            'Uso de técnicas e instrumentos.',
            'Aplicación rigurosa de técnicas.',
            'Calidad en análisis e interpretación.'
        ]],
        '7.2 Resultados Obtenidos' => ['obs' => 'resultados_obs', 'nota' => 'resultados_nota', 'criterios' => [
            'Coherencia entre resultados, problema y objetivos.',
            'Utilidad e impacto práctico.',
            'Novedad investigativa.',
            'Originalidad y creatividad.',
            'Cumplimiento de derechos de autor.'
        ]],
        '7.3 Presentación del Documento' => ['obs' => 'presentacion_obs', 'nota' => 'presentacion_nota', 'criterios' => [
            'Organización conforme a normas académicas.',
            'Redacción y ortografía.',
            'Uso adecuado de citas.'
        ]],
    ];
@endphp

@foreach($categorias as $titulo => $data)
<div class="card">
    <h2>{{ $titulo }}</h2>
    <ul>
        @foreach($data['criterios'] as $criterio)
            <li>{{ $criterio }}</li>
        @endforeach
    </ul>
    <strong>Observaciones:</strong>
    <div class="scrollable-textarea">{{ $datos[$data['obs']] ?? 'N/A' }}</div>
    <p><strong>Nota:</strong> {{ $datos[$data['nota']] ?? 'N/A' }}</p>
</div>
@endforeach
{{-- Aspectos Complementarios --}}
<div class="card">
    <h2>Aspectos Complementarios</h2>
    <p><strong>¿El documento viene acompañado de una carta de aprobación de la empresa?</strong>
        {{ $datos['normas'] ?? 'N/A' }}
    </p>
    <p><strong>¿Se ha verificado la ejecución del proyecto en la empresa?</strong>
        {{ $datos['redaccion'] ?? 'N/A' }}
    </p>
</div>

{{-- Evaluación Final Proyecto de Grado --}}
<div class="card">
    <h2>Evaluación Final Proyecto de Grado</h2>

    <h3>Informe Final (40%)</h3>
    <p><strong>Desarrollo metodológico:</strong> {{ $datos['nota_metodologico'] ?? 'N/A' }}</p>
    <p><strong>Resultados obtenidos:</strong> {{ $datos['nota_resultados'] ?? 'N/A' }}</p>
    <p><strong>Presentación del documento:</strong> {{ $datos['nota_presentacion'] ?? 'N/A' }}</p>
    <p><strong>Nota evaluación informe final:</strong> {{ $datos['nota_eval_informe'] ?? 'N/A' }}</p>
    <p><strong>Nota final (2 cifras):</strong> {{ $datos['nota_final_informe'] ?? 'N/A' }}</p>
    <strong>Observación:</strong>
    <div class="scrollable-textarea">{{ $datos['observacion_informe_final'] ?? 'N/A' }}</div>

    <h3>Sustentación (60%)</h3>
    <p><strong>Exposición clara:</strong> {{ $datos['nota_exposicion'] ?? 'N/A' }}</p>
    <p><strong>Resultados presentados:</strong> {{ $datos['nota_resultados_sustentacion'] ?? 'N/A' }}</p>
    <p><strong>Respuestas acertadas:</strong> {{ $datos['nota_respuestas'] ?? 'N/A' }}</p>
    <p><strong>Medios empleados:</strong> {{ $datos['nota_medios'] ?? 'N/A' }}</p>
    <p><strong>Nota evaluación sustentación:</strong> {{ $datos['nota_eval_sustentacion'] ?? 'N/A' }}</p>
    <p><strong>Nota final (2 cifras):</strong> {{ $datos['nota_final_sustentacion'] ?? 'N/A' }}</p>
    <strong>Observación:</strong>
    <div class="scrollable-textarea">{{ $datos['observacion_sustentacion'] ?? 'N/A' }}</div>

    <h3>Nota Final Proyecto de Grado</h3>
    <p><strong>Evaluación informe final (40%):</strong> {{ $datos['nota_informe_final_grado'] ?? 'N/A' }}</p>
    <p><strong>Evaluación sustentación (60%):</strong> {{ $datos['nota_sustentacion_grado'] ?? 'N/A' }}</p>
    <p><strong>Nota final proyecto de grado:</strong> {{ $datos['nota_final_grado'] ?? 'N/A' }}</p>
    <p><strong>Nota final (2 cifras):</strong> {{ $datos['nota_final_dos_cifras'] ?? 'N/A' }}</p>
    <strong>Observación:</strong>
    <div class="scrollable-textarea">{{ $datos['observacion_final_grado'] ?? 'N/A' }}</div>
</div>

{{-- Botón Descargar --}}
<form action="{{ route('evaluacion_final.pdf') }}" method="POST" class="text-center">
    @csrf
    @foreach($datos as $campo => $valor)
      <input type="hidden" name="{{ $campo }}" value="{{ $valor }}">
    @endforeach
    <button type="submit" class="button-download">Descargar Acta de Evaluación Final</button>
</form>

</body>
</html>

