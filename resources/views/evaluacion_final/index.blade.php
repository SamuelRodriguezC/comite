<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Evaluación Final</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
<div class="container py-5">
    <h1 class="mb-5 text-center fw-bold">ACTA DE EVALUACIÓN FINAL</h1>

    <form action="{{ route('evaluacion_final.procesar') }}" method="POST">
        @csrf

        <!-- Información General -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Datos Generales</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Título del Proyecto</label>
                    <input type="text" class="form-control" name="titulo" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Estudiante 1</label>
                        <input type="text" class="form-control" name="nombre1" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Código</label>
                        <input type="text" class="form-control" name="codigo1" required>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre Estudiante 2</label>
                        <input type="text" class="form-control" name="nombre2">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Código</label>
                        <input type="text" class="form-control" name="codigo2">
                    </div>
                </div>

                <label class="form-label">Alternativa de Grado</label>
                <div class="mb-3">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="alternativa" value="Monografía" required>
                        <label class="form-check-label">Monografía</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="alternativa" value="Artículo">
                        <label class="form-check-label">Artículo de investigacion</label>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Director del Proyecto</label>
                        <input type="text" class="form-control" name="director" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre del Jurado 1</label>
                        <input type="text" class="form-control" name="jurado1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Nombre del Jurado 2</label>
                        <input type="text" class="form-control" name="jurado2" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Escala de evaluación -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Escala de Evaluación</div>
            <div class="card-body">
                <p class="small mb-2">
                    <strong>5 – 4.5:</strong> Excelente nivel de calidad <br>
                    <strong>4.4 – 4.0:</strong> Buen nivel de calidad <br>
                    <strong>3.9 – 3.5:</strong> Aceptable nivel de calidad <br>
                    <strong>3.4 – 3.0:</strong> Insuficiente nivel de calidad <br>
                    <strong>Menos de 3.0:</strong> Bajo nivel de calidad
                </p>
                <p class="small fst-italic">
                    Cada miembro del jurado deberá generar una nota final sobre el trabajo de grado. La evaluación final tendrá nota Aprobatoria cuando el resultado final sea de tres puntos seis (3.6) o superior en la escala de 1 a 5. Si existe diferencia en el concepto final asignado por los miembros del jurado sobre el trabajo de grado (uno aprobatorio y otro reprobando), se nombrará un tercer miembro para dirimir la situación, quien calificará de forma independiente y se computará con las notas de los anteriores docentes ya nombrados.
                </p>
            </div>
        </div>

        <!-- Categorías de Evaluación -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Categorías de Evaluación Informe final</div>
            <div class="card-body">

                <!-- 7.1 Desarrollo Metodológico -->
                <div class="mb-4">
                    <h5>7.1 Desarrollo Metodológico. <p>las consideraciones a tener en cuenta para evaluar este componente son: </p></h5>
                    <ul class="small">
                        <li>Gado de Coherencia entre el problema de investigación y el diseño de la investigacion propuesta</li>
                        <li>Uso de tecnicas de recoleccion de informacion, instrumentos y procediminetos estadisticos, asi como de tecnivas cuantitativas pertinentes, de acuerdo con el diseño de la investigacion propuesta.</li>
                        <li>La utlizacion de las tecnicas seleccionadas fue aplicada de forma correcta, rigurosa y pertinente.</li>
                        <li>Exisrw calidad en los analisis e imterpretacion de la informacion</li>
                    </ul>
                    <textarea class="form-control mb-2" name="desarrollo_obs" rows="3" placeholder="Observaciones Desarrollo Metodológico"></textarea>
                    <input type="number" step="0.1" min="1" max="5" class="form-control" name="desarrollo_nota" placeholder="Nota Desarrollo Metodológico (1-5)">
                </div>

                <!-- 7.2 Resultados Obtenidos -->
                <div class="mb-4">
                    <h5>7.2 Resultados Obtenidos <p>las consideraciones para tener en cuenta para evualuar este componente son</p></h5>
                    <ul class="small">
                        <li>Coherencia: Existe coherencia entre los resultados del proyecto, el problema formulado y los objetivos propuestos </li>
                        <li>Utilidad e impacto: Se obtuvienro resultados que puedan ser aplicados  en un contexto practico, segun el objeto de estudio</li>
                        <p>los siguientes aspetos, deben ser cosiderados especialmente para el caso de proyectos de investigacion, aun asi, dependiendo de los resultados del proyecto, podrian ser considerados para el proyecto de grado, en los cuales se evidencie un aporte desde el punto de vista investigativo: </p>
                        <li>Novedad investigativa: Se evidencia que los resultados del proyecto aportan nuevos elementos metodologicos, de analisis o teoricos frente al problema de investigacion propuesto</li>
                        <li>Originalidad: se evidencia Originalidad, creatividad e innovacion para proponer la solucion al problema de investigacion, asi como recursividad en la solucion propuesta.</li>
                        <li>Cumplimiento de derechos de autor y propiedad intelectual.</li>
                    </ul>
                    <textarea class="form-control mb-2" name="resultados_obs" rows="3" placeholder="Observaciones Resultados Obtenidos"></textarea>
                    <input type="number" step="0.1" min="1" max="5" class="form-control" name="resultados_nota" placeholder="Nota Resultados Obtenidos (1-5)">
                </div>

                <!-- 7.3 Presentación del Documento -->
                <div class="mb-4">
                    <h5>7.3 Presentación del Documento <p>las consideraciones a tener en cuenta para evaluar este componente son</p></h5>
                    <ul class="small">
                        <li>Organizacion del documento: se hace uso correcto de las normas para la presentacion de trabajos escritos.</li>
                        <li>Redacción y ortografía: la redaccion del documento es adecuada y la ortografia es correcta</li>
                        <li>Se hace adecuado de citas bibliograficas</li>
                    </ul>
                    <textarea class="form-control mb-2" name="presentacion_obs" rows="3" placeholder="Observaciones Presentación del Documento"></textarea>
                    <input type="number" step="0.1" min="1" max="5" class="form-control" name="presentacion_nota" placeholder="Nota Presentación del Documento (1-5)">
                </div>
            </div>
        </div>

        <!-- Aspectos Complementarios -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Aspectos Complementarios</div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">¿El documento viene acompañado de una carta de aprobacion de la empresa?</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="normas" value="Sí" required>
                        <label class="form-check-label">Sí</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="normas" value="No">
                        <label class="form-check-label">No</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">¿Se ha verificado la ejecucion del proyecto en la empresa?</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="redaccion" value="Adecuada" required>
                        <label class="form-check-label">Adecuada</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="redaccion" value="Deficiente">
                        <label class="form-check-label">Deficiente</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Evaluación Final -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Evaluación Final Proyecto de Grado</div>
            <div class="card-body">
                <!-- Manteniendo todos los campos tal como estaban -->
                <div class="mb-3">
                    <h4>Evaluación informe final proyecto (40%)</h4>
                </div>
                <div class="mb-3">
                    <p>Desarrollo metodologico (40%)</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_metodologico" placeholder="Ejemplo: 3.85">
                </div>

                <div class="mb-3">
                    <p>Resultados Obtenidos (40%)</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_resultados" placeholder="Ejemplo: 3.85">
                </div>
                <div class="mb-3">
                    <p>Presentación del documento (20%)</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_presentacion" placeholder="Ejemplo: 3.85">
                </div>
                <div class="mb-3">
                    <p>Nota evaluación informe final</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_eval_informe" placeholder="Ejemplo: 3.00">
                </div>
                <div class="mb-3">
                    <p>Nota final con dos cifras significativas</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_final_informe" placeholder="Ejemplo: 3.00">
                </div>
                <div class="mb-3">
                    <label class="form-label">Observación sobre informe final proyecto</label>
                    <textarea class="form-control" name="observacion_informe_final" rows="3" placeholder="Escribe aquí tus observaciones..."></textarea>
                </div>
                <!-- Sustentación -->
                <div class="mb-3">
                    <h4 class="form-label">Evaluación sustentación proyecto (60%)</h4>
                    <p> La exposición es clara y demuestra dominio del tema (20%)</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_exposicion" placeholder="Ejemplo: 3.85">
                    <p>Se presentan los resultados del proyecto de forma clara y precisa (35%)</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_resultados_sustentacion" placeholder="Ejemplo: 3.85">
                    <p>Las respuestas a las inquietudes propuestas son acertadas y aclaradoras (35%)</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_respuestas" placeholder="Ejemplo: 3.85">
                    <p> Los medios empleados son adecuados (10%)</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_medios" placeholder="Ejemplo: 3.85">
                    <p>Nota Evaluación sustentación</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_eval_sustentacion" placeholder="Ejemplo: 3.00">
                    <p>Nota final con dos cifras significativas</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_final_sustentacion" placeholder="Ejemplo: 3.00">
                    <label class="form-label">Observación sobre evaluación sustentación de proyecto</label>
                    <textarea class="form-control" name="observacion_sustentacion" rows="3" placeholder="Escribe aquí tus observaciones..."></textarea>
                </div>
                <!-- Nota final proyecto de grado -->
                <div class="mb-3">
                    <div class="card-header bg-dark text-white fw-bold">Evaluación Final de proyecto de grado</div>
                    <p>Evaluación informe final proyecto (40%)</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_informe_final_grado" placeholder="Ejemplo: 3.85">
                    <p>Evaluación sustentación proyecto (60%)</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_sustentacion_grado" placeholder="Ejemplo: 3.85">
                    <p>Nota final proyecto de grado</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_final_grado" placeholder="Ejemplo: 3.00">
                    <p>Nota final con dos cifras significativas</p>
                    <input type="number" step="0.01" min="1" max="5" class="form-control" name="nota_final_dos_cifras" placeholder="Ejemplo: 3.00">
                    <label class="form-label">Observación sobre evaluación final proyecto de grado</label>
                    <textarea class="form-control" name="observacion_final_grado" rows="3" placeholder="Escribe aquí tus observaciones..."></textarea>
                </div>
            </div>
        </div>

        <!-- Firmas -->
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-dark text-white fw-bold">Firmas</div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre del Jurado</label>
                        <input type="text" class="form-control mb-2" name="nombre_jurado" placeholder="Nombre completo jurado">
                        <p>__________________________</p>
                        <p>Firma Jurado</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nombre del Evaluador</label>
                        <input type="text" class="form-control mb-2" name="nombre_evaluador" placeholder="Nombre completo evaluador">
                        <p>__________________________</p>
                        <p>Firma Evaluador</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary btn-lg">Enviar</button>
        </div>

    </form>
</div>
</body>
</html>
