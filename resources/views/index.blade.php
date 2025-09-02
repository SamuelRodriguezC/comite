<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluación Anteproyecto de Grado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold">ACTA DE EVALUACIÓN DE ANTEPROYECTO</h1>

    <form action="{{ route('formulario.procesar') }}" method="POST">
        @csrf

        <!-- Datos Generales -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Datos Generales</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título del Anteproyecto</label>
                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                </div>

                <h5 class="mb-3">Información de los Estudiantes</h5>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="nombre1" class="form-label">Nombre estudiante 1</label>
                        <input type="text" class="form-control" id="nombre1" name="nombre1" required>
                    </div>
                    <div class="col-md-6">
                        <label for="codigo1" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo1" name="codigo1" required>
                    </div>
                </div>
                <div class="row g-3 mb-2">
                    <div class="col-md-6">
                        <label for="nombre2" class="form-label">Nombre estudiante 2</label>
                        <input type="text" class="form-control" id="nombre2" name="nombre2">
                    </div>
                    <div class="col-md-6">
                        <label for="codigo2" class="form-label">Código</label>
                        <input type="text" class="form-control" id="codigo2" name="codigo2">
                    </div>
                </div>
            </div>
        </div>

        <!-- Información de la Organización -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Información de la Organización</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="empresa" class="form-label">Nombre de la empresa u organización</label>
                    <input type="text" class="form-control" id="empresa" name="empresa">
                </div>
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <input type="text" class="form-control" id="direccion" name="direccion">
                </div>
                <div class="mb-3">
                    <label for="contacto" class="form-label">Nombre de contacto</label>
                    <input type="text" class="form-control" id="contacto" name="contacto">
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="cargo" name="cargo">
                    </div>
                    <div class="col-md-4">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="col-md-4">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo">
                    </div>
                </div>
            </div>
        </div>

        <!-- Categorías de Evaluación -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Categorías de Evaluación (Sí/No)</div>
            <div class="card-body">
                @php
                    $criterios = [
                        "Articulación institucional: ¿La temática tiene relación con las líneas de investigación y ejes temáticos?",
                        "Fundamentación: ¿La temática tiene relación con la formación en básica de ingeniería(ingenieria ambiental, ingenieria de industrial, ingenieria de sistemas, ingenieria mecanica, ¿ingenieria en Ciencia de Datos)?",
                        "Cuantificación del problema: ¿En el planteamiento del problema existe una suficiente cuantificación de este?",
                        "Descripción del problema: ¿La descripcion del problema identifica causas, consecuencias, actores involucrados, dimensiones de analisis, y deja total claridad sobre su naturaleza?",
                        "Pertinencia del anteproyecto: ¿La propuesta es suficiente para cumplir el requisito exigido como trabajo de grado para optar al titulo profesional?",
                        "Formulación del problema: ¿El enunciado del problema es comprensible?",
                        "Formulación del problema: ¿Existe coherencia entre la formulacion de la pregunta y la descripcion del problema?",
                        "Formulación del problema: ¿Existe una relacion entre causas y efectos manifestada claramente en la pregunta problema?",
                        "Justificación: ¿Se describe la utilidad que tendran los resultados del proyecto (tecnico, economico, social, ambientalm donde se justifique)?",
                        "Plantamiento de Objetivos: ¿El objetivo general responde a la formulacion del problema?",
                        "Plantamiento de Objetivos: ¿Los objetivos especificos son coherentes con el objetivo general?",
                        "Definición del alcance: ¿Las tematicas estan correctamente delimitadas?",
                        "Definición del alcance: ¿Se han clarificado las caracteristicas de poblaciones y muestras a estudiar?",
                        "Definición del alcance: ¿Se han definido los procesos en los que se va a trabajar?",
                        "Definición del alcance: ¿Se definio la ubicacion geografica del objeto de estudio?",
                        "Definición del alcance: ¿se han establecido el nivel de implementacion de las soluciones a obtener?",
                        "Definición del alcance: ¿se ha planificado el tiempo que va a durar el proyecto?",
                        "Definición del alcance: ¿se presenta el impacto social, ambiental y economico del proyecto?",
                        "Antecedentes: ¿Existen referentes locales, nacionales e internacionales documentados?",
                        "Antecedentes: ¿Existen conclusiones analiticas sobre estos antecedentes que puedan ser considerados como guias para el marco teorico?",
                        "Marco teórico: ¿Existe una relacion directa entre los temas tratados con los objetivos especificos de manera que se responda a ellos de manera suficiente y coherente?",
                        "Marco conceptual: ¿Los contenidos permiten comprender la forma como se entiende cada uno en el contexto del proyecto?",
                        "Marco conceptual: ¿Los conceptos incluidos han presentados bajo la propia autoria del estudiante?",
                        "Metodología: ¿Se describe de forma completa y precisa las actividades y procedimientos necesarios para dar respuesta a los objetivos propuestos?",
                        "Metodología: ¿Se propone el uso de metodologias propias de la ingenieria?",
                        "Metodología: ¿El cronograma esta bien planteado de acuerdo con la metodologia planteada?",
                        "Metodología: ¿En caso de haber formulado el proyecto con marco logico, ¿se evidencian los criterios de la logica vertical horizontal para el marco logico formulado?",
                        "Presupuesto: ¿Se distuguen los recursos requeridos de personal, materiales y equipos y gastos generales?",
                        "Presentación: ¿El documento esta escrito de acuerdo con la plantilla sugerida por la facultad de ingenieria?",
                        "Presentación: ¿El sistema de referenciacion utilizado con el documento esta acorde con la norma IEEE?",
                        "Presentación: ¿La redaccion del documento es adecuada y la ortografia es correcta?",
                    ];
                @endphp

                @foreach ($criterios as $index => $texto)
                    <div class="mb-3">
                        <label class="form-label">{{ $texto }}</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="criterio{{ $index }}" id="criterio{{ $index }}_si" value="Si" required>
                            <label class="form-check-label" for="criterio{{ $index }}_si">Sí</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="criterio{{ $index }}" id="criterio{{ $index }}_no" value="No">
                            <label class="form-check-label" for="criterio{{ $index }}_no">No</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Concepto Final -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Concepto Final</div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="concepto" class="form-label">Concepto del Jurado</label>
                    <select class="form-select" id="concepto" name="concepto" required>
                        <option value="">Seleccione...</option>
                        <option value="aprobado">Anteproyecto aprobado</option>
                        <option value="no_aprobado">Anteproyecto no aprobado</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Firmas -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Firmas</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-12">
                        <label for="firma" class="form-label">Nombre y firma del Jurado</label>
                        <input type="text" class="form-control mb-2" id="firma" name="firma" placeholder="Nombre completo del jurado">
                        <p class="my-3">__________________________</p>
                        <p class="text-muted">Firma del Jurado</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botón -->
        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg">Enviar</button>
        </div>

    </form>
</div>
</body>
</html>
