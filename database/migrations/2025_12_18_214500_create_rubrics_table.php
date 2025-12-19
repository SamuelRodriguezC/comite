<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            Schema::create('rubrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade'); 
 // Asegúrate de que la tabla 'courses' exista
            $table->string('name');
            $table->text('description')->nullable();
            $table->longText('competencies_results_grades')->nullable();
            $table->string('performance_level')->nullable();
            $table->longText('level_descriptions')->nullable();
            $table->string('academic_period')->nullable();
            $table->string('status')->default('Habilitado');
            $table->timestamps();
            $table->text('resultados_aprendizaje')->nullable();
        });

   

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrics');
        $table->dropColumn('resultados_aprendizaje');

    }
};
