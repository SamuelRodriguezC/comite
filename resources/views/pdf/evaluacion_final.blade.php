<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8" />
<title>Acta Evaluación Final</title>
<style>
  body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    font-size: 14px;
    margin: 20px;
    color: #111;
    background: #fff;
  }
  h2, h3 {
    text-align: center;
    margin: 0 0 10px 0;
  }
  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    font-size: 14px;
  }
  th, td {
    border: 1px solid #111;
    padding: 8px 12px;
    vertical-align: top;
  }
  th {
    background-color: #f2f2f2;
    font-weight: 600;
    text-align: left;
  }
  ul {
    margin: 0 0 10px 18px;
    padding: 0;
  }
  .signature-block {
    width: 45%;
    display: inline-block;
    text-align: center;
    margin-top: 40px;
  }
  .signature-line {
    border-bottom: 1px solid #111;
    margin: 40px 0 5px 0;
  }
  .footer-text {
    font-size: 11px;
    text-align: justify;
    margin-top: 30px;
  }
  .table-section {
    background-color: #f5f5f5;
    font-weight: 600;
    text-align: center;
  }
</style>
</head>
<body>

<!-- Encabezado -->
<table style="width: 100%; border: 1px solid #000; border-collapse: collapse; margin-bottom: 20px;">
    <tr>
        <!-- Logo -->
        <td style="width: 20%; text-align: center; border: 1px solid #000; padding: 5px;">
            <img src="images/logoUniversidad.png" alt="Logo U" style="height: 70px; width: auto;">
        </td>

        <!-- Universidad y Facultad -->
        <td style="width: 40%; text-align: center; border: 1px solid #000; padding: 5px;">
            <strong>UNIVERSIDAD LIBRE</strong><br>
            <span style="font-size: 14px;">FAC. INGENIERÍA</span><br>
            <span style="font-size: 12px;">CENTRO DE INVESTIGACIÓN</span>
        </td>

        <!-- Título del documento -->
        <td style="width: 40%; text-align: center; border: 1px solid #000; padding: 5px;">
            <span style="font-size: 14px; font-weight: bold;">
                EVALUACIÓN OPCIONES CON COMPONENTE DE INVESTIGACIÓN
            </span>
        </td>
    </tr>
</table>


<table><
  <tr class="table-section"><th colspan="2">Datos Generales</th></tr>
  <tr><th style="width:30%;">Título</th><td>{{ $datos['titulo'] ?? '' }}</td></tr>
  <tr><th>Estudiante 1</th><td>{{ $datos['nombre1'] ?? '' }} - Código: {{ $datos['codigo1'] ?? '' }}</td></tr>
  <tr><th>Estudiante 2</th><td>{{ $datos['nombre2'] ?? '' }} - Código: {{ $datos['codigo2'] ?? '' }}</td></tr>
  <tr><th>Alternativa de grado</th><td>{{ $datos['alternativa'] ?? '' }}</td></tr>
  <tr><th>Director</th><td>{{ $datos['director'] ?? '' }}</td></tr>
  <tr><th>Jurados</th><td>1. {{ $datos['jurado1'] ?? '' }} <br>2. {{ $datos['jurado2'] ?? '' }}</td></tr>

  <!-- Escala de evaluación -->
  <tr class="table-section"><th colspan="2">Escala de Evaluación</th></tr>
  <tr>
    <td colspan="2">
      <strong>Escala:</strong><br>
      5 – 4.5 Excelente nivel de calidad <br>
      4.4 – 4.0 Buen nivel de calidad <br>
      3.9 – 3.5 Aceptable nivel de calidad <br>
      3.4 – 3.0 Insuficiente nivel de calidad <br>
      Menos de 3.0 Bajo nivel de calidad <br><br>
      “Cada miembro del jurado deberá generar una nota final sobre el trabajo de grado.
      La evaluación final tendrá nota Aprobatoria cuando el resultado final sea de tres puntos seis (3.6) o superior en la escala de 1 a 5.
      Si existe diferencia en el concepto final asignado por los miembros del jurado sobre el trabajo de grado (uno aprobatorio y otro reprobando),
      se nombrará un tercer miembro para dirimir la situación, quien calificará de forma independiente y se computará con las notas de los anteriores docentes ya nombrados.”
    </td>
  </tr>

  <tr class="table-section"><th colspan="2">Categorías de Evaluación</th></tr>
  <tr><th>7.1 Desarrollo Metodológico</th>
      <td>
        <ul>
          <li>Grado de coherencia entre el problema de investigacion y el diseño de la investigacion propuesta.</li>
          <li>Uso de tecnicas de recoleccion de informacion, instrumentos y procedimientos estadisticos, asi como de tecnicas cuantitativas pertinentes, de acuerdo con el diseño de la investigacion propuesta.</li>
          <li>La utilizacion de las tecnicas seleccionadas fue aplicada de forma correcta, rigurosa y pertinente</li>
          <li>Existen calidad en los analisis e interpretacion de la informacion.</li>
        </ul>
        <strong>Observaciones:</strong> {{ $datos['desarrollo_obs'] ?? '' }} <br>
        <strong>Nota:</strong> {{ $datos['desarrollo_nota'] ?? '' }}
      </td>
  </tr>
  <tr><th>7.2 Resultados Obtenidos</th>
      <td>
        <ul>
          <li>Coherencia: Existe coherencia entre los resultados del proyecto, el problema formulado y los objetivos propuestos</li>
          <li>Utilidad e impacto: Se obtuvieron resultados que puedan ser aplicados en un contexto practico, segun el objeto de estudio.</li>
          </ul>
          <p>los siguientes aspectos, deben ser considerados especialmente para el caso de proyectos de investigacion, aun asi, dependiendo de los resultados del proyecto, podrian ser considerados para proyectos de grado, en los cuales se evidencie un aporte desde el punto de vista investigativo: </p>
          <ul>
          <li>novedad investigativa: se evidencia que los resultados del proyecto aportan nuevos elementos metodologicos, de analisis o teoricos frente al problema de investigacion propuesto</li>
          <li>Originalidad: se evidencia originalidad, creatividad e innovacion para proponer la solucion al problema de investigacion, asi como recursividad en la solucion propuesta.</li>
          <li>Cumplimiento de derechos de autor y propiedad intelectual.</li>
          </ul>
        <strong>Observaciones:</strong> {{ $datos['resultados_obs'] ?? '' }} <br>
        <strong>Nota:</strong> {{ $datos['resultados_nota'] ?? '' }}
      </td>
  </tr>
  <tr><th>7.3 Presentación del Documento</th>
      <td>
        <ul>
          <li>Organización del Documento: se hace uso correcto de las normas para la presentacion de trabajos escritos.</li>
          <li>Redaccion y ortografia: la redaccion del documento es adecuada y la ortografia es correcta</li>
          <li>se hace uso adecuado de citas bibliograficas</li>
        </ul>
        <strong>Observaciones:</strong> {{ $datos['presentacion_obs'] ?? '' }} <br>
        <strong>Nota:</strong> {{ $datos['presentacion_nota'] ?? '' }}
      </td>
  </tr>

  <tr class="table-section"><th colspan="2">Aspectos Complementarios</th></tr>
  <tr><th>¿El documento viene acompañado de una carta de aprobacion de la empresa?</th><td>{{ $datos['normas'] ?? '' }}</td></tr>
  <tr><th>¿se ha verificado la ejecucion del proyecto en la empresa?</th><td>{{ $datos['redaccion'] ?? '' }}</td></tr>
<tr class="table-section"><th colspan="2">Evaluación Informe Final Proyecto 40%</th></tr>
<tr><th>Desarrollo Metodológico (40%)</th><td>{{ $datos['nota_metodologico'] ?? '' }}</td></tr>
<tr><th>Resultados Obtenidos (40%)</th><td>{{ $datos['nota_resultados'] ?? '' }}</td></tr>
<tr><th>Presentación del Documento (20%)</th><td>{{ $datos['nota_presentacion'] ?? '' }}</td></tr>
<tr><th>Nota Evaluación Informe Final</th><td>{{ $datos['nota_eval_informe'] ?? '' }}</td></tr>
<tr><th>Nota Final Con dos cifras significativas</th><td>{{ $datos['nota_final_informe'] ?? '' }}</td></tr>
<tr><th>Observaciones Informe Final</th><td>{{ $datos['observacion_informe_final'] ?? '' }}</td></tr>
<tr class="table-section"><th colspan="2">Evaluación Sustentacion proyecto 60%</th></tr>
<tr><th>La exposicion es clara y demuestra dominio del tema (20%)</th><td>{{ $datos['nota_exposicion'] ?? '' }}</td></tr>
<tr><th>Se presentan los resultados del proyecto de forma clara y precisa (35%)</th><td>{{ $datos['nota_resultados_sustentacion'] ?? '' }}</td></tr>
<tr><th>Las respuestas a las inquietudes propuestas son acertadas y aclaradoras (35%)</th><td>{{ $datos['nota_respuestas'] ?? '' }}</td></tr>
<tr><th>Los medios empleados son adecuados (10%)</th><td>{{ $datos['nota_medios'] ?? '' }}</td></tr>
<tr><th>Nota Evaluación Sustentación</th><td>{{ $datos['nota_eval_sustentacion'] ?? '' }}</td></tr>
<tr><th>Nota Final con dos cifras significativas</th><td>{{ $datos['nota_final_sustentacion'] ?? '' }}</td></tr>
<tr><th>Observación Sustentación</th><td>{{ $datos['observacion_sustentacion'] ?? '' }}</td></tr>
<tr class="table-section"><th colspan="2">Evaluación Final Proyecto de Grado</th></tr>
<tr><th>Evaluación Informe Final Proyecto (40%)</th><td>{{ $datos['nota_informe_final_grado'] ?? '' }}</td></tr>
<tr><th>Evaluación Sustentación Proyecto (60%)</th><td>{{ $datos['nota_sustentacion_grado'] ?? '' }}</td></tr>
<tr><th>Nota Final Proyecto de Grado</th><td>{{ $datos['nota_final_grado'] ?? '' }}</td></tr>
<tr><th>Nota Final con dos cifras significativas</th><td>{{ $datos['nota_final_dos_cifras'] ?? '' }}</td></tr>
<tr><th>Observación Final Proyecto de Grado</th><td>{{ $datos['observacion_final_grado'] ?? '' }}</td></tr>
</table>
<!-- Firmas -->
<div class="mb-4 shadow-sm card">
    <div class="card-body">
        <table style="width: 100%; text-align: center;">
            <tr>
                <!-- Firma Jurado -->
                <td style="width: 50%; padding: 20px;">
                    <p><strong>{{ $datos['nombre_jurado'] ?? 'Nombre del Jurado' }}</strong></p>
                    <p>__________________________</p>
                    <p>Firma Jurado</p>
                </td>
                <!-- Firma Evaluador -->
                <td style="width: 50%; padding: 20px;">
                    <p><strong>{{ $datos['nombre_evaluador'] ?? 'Nombre del Evaluador' }}</strong></p>
                    <p>__________________________</p>
                    <p>Firma Evaluador</p>
                </td>
            </tr>
        </table>
    </div>
</div>




<div class="footer-text">
  “El jurado tendrá un plazo máximo de quince (15) días hábiles para hacer lectura completa del mismo,
  y diligenciar el formato de concepto de evaluación definido por el comité del respectivo programa.
  El estudiante tendrá un mes de plazo para hacer las correcciones y el director enviará de nuevo por correo institucional
  al jurado, quien tendrá quince (15) días hábiles para evaluarlo nuevamente y emitir el concepto respectivo.
  El estudiante tiene derecho a presentar una (1) corrección al anteproyecto.”
  <br><br><strong>Reglamento de opciones de grado, 2015.</strong>
</div>

</body>
</html>
