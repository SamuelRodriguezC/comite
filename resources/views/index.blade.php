<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Evaluación Anteproyecto de Grado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f5f7;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        header {
            background: linear-gradient(135deg, #2e2e2e, #4b4b4b);
            color: white;
            padding: 35px 0;
            margin-bottom: 40px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        header h1 {
            font-size: 2.2rem;
            font-weight: bold;
            margin: 0;
            letter-spacing: 1px;
        }
        .logo {
            max-height: 120px; /* más pequeño y proporcionado */
            margin-right: 15px;
        }
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 6px 16px rgba(0,0,0,0.08);
        }
        .card-header {
            background-color: #f8f9fa !important;
            font-weight: 600;
            font-size: 1.1rem;
            color: #444;
            border-bottom: 1px solid #e5e5e5;
            border-radius: 1rem 1rem 0 0 !important;
        }
        .form-control, .form-select {
            border-radius: 0.6rem;
            border: 1px solid #dcdcdc;
            padding: 10px;
        }
        .form-control:focus, .form-select:focus {
            border-color: #aaa;
            box-shadow: 0 0 0 0.2rem rgba(100,100,100,0.25);
        }
        label {
            font-weight: 500;
            color: #555;
        }
        .btn-submit {
            background: #333;
            color: white;
            font-weight: 600;
            border-radius: 0.7rem;
            padding: 12px 28px;
            font-size: 1.1rem;
            transition: all 0.3s ease-in-out;
            border: none;
        }
        .btn-submit:hover {
            background: #555;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .firma-line {
            margin-top: 25px;
            font-style: italic;
            color: #777;
        }
        footer {
            margin-top: 50px;
            text-align: center;
            color: #888;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header class="text-center">
        <div class="d-flex align-items-center justify-content-center mb-5">
         <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
         <h1 class="fw-bold text-center m-0">ACTA DE EVALUACIÓN DE ANTEPROYECTO</h1>
        </div>
    </header>

    <!-- Contenido -->
    <div class="container">

        <form action="{{ route('formulario.pdf', ['transaction_id' => $transaction_id]) }}" method="POST">
            @csrf
            <input type="hidden" name="transaction_id" value="{{ $transaction_id }}">
            <input type="hidden" name="type" value="3">

            <!-- Datos Generales + Estudiantes -->
            <div class="card mb-4">
                <div class="card-header">Datos Generales</div>
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

            <!-- Información Organización -->
            <div class="card mb-4">
                <div class="card-header">Información de la Organización</div>
                <div class="card-body row g-3">
                    <div class="col-md-6">
                        <label for="empresa" class="form-label">Nombre de la empresa</label>
                        <input type="text" class="form-control" id="empresa" name="empresa">
                    </div>
                    <div class="col-md-6">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion">
                    </div>
                    <div class="col-md-6">
                        <label for="contacto" class="form-label">Nombre de contacto</label>
                        <input type="text" class="form-control" id="contacto" name="contacto">
                    </div>
                    <div class="col-md-3">
                        <label for="cargo" class="form-label">Cargo</label>
                        <input type="text" class="form-control" id="cargo" name="cargo">
                    </div>
                    <div class="col-md-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="col-md-6">
                        <label for="correo" class="form-label">Correo electrónico</label>
                        <input type="email" class="form-control" id="correo" name="correo">
                    </div>
                </div>
            </div>

            <!-- Categorías Evaluación -->
            <div class="card mb-4">
                <div class="card-header">Categorías de Evaluación</div>
                <div class="card-body">
                    @php
                        $criterios = [
                            "4.1    Articulación institucional: ¿La temática tiene relación con las líneas de investigación y ejes temáticos?",
                            "4.2    Fundamentación: ¿La temática tiene relación con la formación en básica de ingeniería(ingenieria ambiental, ingenieria de industrial, ingenieria de sistemas, ingenieria mecanica, ingenieria en cienca de datos?",
                            "4.3    Cuantificación del problema: ¿En el planteamiento del problema existe una suficiente cuantificación de este?",
                            "4.4    Descripción del problema: ¿La descripción del problema identifica causas, consecuencias, actores involucrados, dimensiones de análisis, y deja total claridad sobre su naturaleza?",
                            "4.5	Pertinencia del anteproyecto: ¿La propuesta es suficiente para cumplir el requisito exigido como trabajo de grado para optar al título profesional?",
                            "4.6	Formulación del problema: ¿El enunciado del problema es comprensible?",
                            "4.7	Formulación del problema: ¿Existe una coherencia entre la formulación de la pregunta y la descripción del problema?",
                            "4.8	Formulación del problema: ¿Existe una relación entre causas y efectos manifestada claramente en la pregunta problema?",
                            "4.9	Justificación: ¿Se describe la utilidad que tendrán los resultados del proyecto (técnico, económico, social, ambiental, donde se justifique)?",
                            "4.10	Planteamiento de objetivos: ¿El objetivo general responde a la formulación del problema?",
                            "4.11	Planteamiento de objetivos: ¿Los objetivos específicos son coherentes con el objetivo general?",
                            "4.12	Definición del alcance: ¿Las temáticas están correctamente delimitadas?",
                            "4.13	Definición del alcance: ¿Se han clarificado las características de poblaciones y muestras a estudiar?",
                            "4.14	Definición del alcance: ¿Se han definido los procesos en los que se va a trabajar?",
                            "4.15	Definición del alcance: ¿Se definido la ubicación geográfica del objeto de estudio?",
                            "4.16	Definición del alcance: ¿Se ha establecido el nivel de implementación de las soluciones a obtener?",
                            "4.17	Definición del alcance: ¿Se ha planificado el tiempo que va a durar el proyecto?",
                            "4.18	Definición del alcance: ¿Se presenta el impacto social, ambiental y económico del proyecto?",
                            "4.19	Antecedentes: ¿Existen referentes locales, nacionales e internacionales documentados?",
                            "4.20	Antecedentes: ¿Existen conclusiones analíticas sobre estos antecedentes que puedan ser consideraros como guías para el marco teórico?",
                            "4.21	Marco teórico: ¿Existe una relación directa entre los temas tratados con los objetivos específicos de manera que se responda a ellos de manera suficiente y coherente?",
                            "4.22	Marco conceptual: ¿Los contenidos permiten comprender la forma como se entiende cada uno en el contexto del proyecto?",
                            "4.23	Marco conceptual: ¿Los conceptos incluidos han presentados bajo la propia autoría del estudiante?",
                            "4.24	Metodología: ¿Se describe de forma completa y precisa las actividades y procedimientos necesarios para dar respuesta a los objetivos propuestos?",
                            "4.25	Metodología: ¿Se propone el uso de metodologías propias de la Ingeniería?",
                            "4.26	Metodología: ¿El Cronograma está bien planteado de acuerdo con la metodología planteada?",
                            "4.27	Metodología: En caso de haber formulado el proyecto con Marco lógico, ¿se evidencian los criterios de lógica vertical y horizontal para el marco lógico formulado?",
                            "4.28	Presupuesto: ¿Se distinguen los recursos requeridos de Personal, Materiales y Equipos, y Gastos generales?",
                            "4.29	Presentación del documento: ¿El documento está escrito de acuerdo con la plantilla sugerida por la facultad de ingeniería?",
                            "4.30	Presentación del documento: ¿El sistema de referenciación utilizado en el documento está acorde con la norma IEEE?",
                            "4.31	Presentación del documento: ¿La redacción del documento es adecuada y la ortografía es correcta?",
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
            <div class="card mb-4">
                <div class="card-header">Concepto Final</div>
                <div class="card-body">
                    <label for="concepto" class="form-label">Concepto del Jurado</label>
                    <select class="form-select" id="concepto" name="concepto" required>
                        <option value="">Seleccione...</option>
                        <option value="aprobado">Anteproyecto aprobado</option>
                        <option value="no_aprobado">Anteproyecto no aprobado</option>
                    </select>
                </div>
            </div>

            <!-- Firmas -->
            <div class="card mb-4">
                <div class="card-header">Firmas</div>
                <div class="card-body text-center">
                    <label for="firma" class="form-label">Nombre y firma del Jurado</label>
                    <input type="text" class="form-control mb-3" id="firma" name="firma" placeholder="Nombre completo del jurado">
                    <div class="firma-line">__________________________</div>
                    <p class="text-muted">Firma del Jurado</p>
                </div>
            </div>

            <!-- Botón -->
            <div class="text-end">
                <button type="submit" class="btn btn-submit">Enviar Acta</button>
            </div>
        </form>
    </div>

    <footer>
        <p>© {{ date('Y') }} Facultad de Ingeniería - Universidad Ejemplo</p>
    </footer>

</body>
</html>
