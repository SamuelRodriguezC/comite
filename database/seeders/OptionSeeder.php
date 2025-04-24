<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptionSeeder extends Seeder
{
    /**
     * Incorporates degree option types with educational level, degree option component, description, and requirements.
     */
    public function run(): void
    {
        // Option::factory()->count(5)->create();
        DB::table('options')->insert([
            ['option' => 'Coterminal', 'level' => '1', 'component' => '2', 'description' => 'Cursar la totalidad de créditos del primer semestre de posgrado.', 'requirement' => '1 Certificado de terminación de carrera o de Créditos aprobados al 75%, 2 Certificado de promedio de carrera en 3.5 para especialización y 3.8 para mestria, 3 Consulta previa del cupo autorizada.'],
            ['option' => 'Curso de perfeccionamiento, profundización o diplomado', 'level' => '1', 'component' => '2', 'description' => 'Actividades académicas tendientes a la actualización, profundización y cualificación concretadas en un trabajo escrito.', 'requirement' => '1 Certificado de terminación de asignaturas, 2 Carta de solicitud al coordinador del proyecto.'],
            ['option' => 'Doble titulación', 'level' => '1', 'component' => '2', 'description' => 'Cursar uno o varios semestres en otra universidad.', 'requirement' => '1 Carta de solicitud al director del programa.'],
            ['option' => 'Curso internacional', 'level' => '1', 'component' => '2', 'description' => 'Curso internacional con intensidad horaria mínima de 120 horas que concluye con un producto académico', 'requirement' => '1 Carta de solicitud a la Coordinación de opciones de grado, 2 Certificado de terminación de asignaturas.'],
            ['option' => 'Plan de negocios', 'level' => '1', 'component' => '2', 'description' => 'Desarrollo y puesta en marcha de un proyecto de emprendimiento e innovación.', 'requirement' => '1 Certificado de créditos aprobado al 80%, 2 Solicitud de opción de grado a la Coordinación de opciones de grado.'],
            ['option' => 'Práctica laboral', 'level' => '1', 'component' => '2', 'description' => 'Práctica laboral sobre asuntos relacionados con el programa académico.', 'requirement' => '1 Certificado de terminación de asignaturas, 2 Carta de solicitud de opción de grado, 3 Contrato laboral.'],
            ['option' => 'Rendimiento académico superior', 'level' => '1', 'component' => '2', 'description' => 'Aprobación de la totalidad de créditos académicos sin reprobar ninguna asignatura.', 'requirement' => '1 Certificación de promedio en 4.6, 2 Certificado de terminación de materias.'],
            ['option' => 'Resultado en las pruebas de estado', 'level' => '1', 'component' => '2', 'description' => 'Percentil alto en la Prueba Saber Pro', 'requirement' => '1 percentil de 75% en competencias genericas y 80% en cada competencia especifica, 2 Carta de solicitud de la opción de grado.'],

            ['option' => 'Monografía', 'level' => '1', 'component' => '1', 'description' => 'proyecto de investigación con forma de monografía', 'requirement' => '1 Anteproyecto, 2 Contactar a un asesor de grado.'],
            ['option' => 'Artículo', 'level' => '1', 'component' => '1', 'description' => 'Documento académico publicable o publicado en una revista científica', 'requirement' => '1 Anteproyecto, 2 Contactar a un director de grado.'],
            ['option' => 'Participación en semillero de investigación', 'level' => '1', 'component' => '1', 'description' => 'Participación en un semillero de investigación avalado por la Universidad.', 'requirement' => '1 Proceso en CENTENARIO, 2 Aprobación del CIFI.'],
            ['option' => 'Participación como auxiliar de investigaciones', 'level' => '1', 'component' => '1', 'description' => 'Presentar un informe de las actividades desarrolladas dentro del proyecto y sus resultados.', 'requirement' => '1 Proceso CENTENARIO, 2 Reporte SIUL.'],

            ['option' => 'Doble titulación', 'level' => '2', 'component' => '2', 'description' => 'Trabajo de grado presentado en otra institución bajo convenio.', 'requirement' => '1 proceso ORI'],

            ['option' => 'Trabajo escrito', 'level' => '2', 'component' => '1', 'description' => 'Desarrollo de un proyecto de investigación.', 'requirement' => '1 Propuesta de trabajo.'],
            ['option' => 'Estancia internacional de investigación', 'level' => '2', 'component' => '1', 'description' => 'Vinculación a un proyecto de investigación de otra institución por un tiempo superior a un mes.', 'requirement' => '1 Vinculación a un proyecto de investigación.'],
            ['option' => 'Artículo científico', 'level' => '2', 'component' => '1', 'description' => 'Texto académico publicado o publicable en una revista reconocida por MinCiencias.', 'requirement' => '1 Prpuesta de artículo, 2 Director de la propuesta.'],
            ['option' => 'Participación como auxiliar de investigaciones', 'level' => '2', 'component' => '1', 'description' => 'Vinculación como auxiliar de investigación a un proyecto o grupo de investigación.', 'requirement' => '1 Proceso CENTENARIO, 2 Solicitud de opción de grado.'],
        ]);
    }
}
