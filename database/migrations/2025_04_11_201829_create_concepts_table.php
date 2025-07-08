<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar migraciones.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('concepts', function (Blueprint $table) {
            $table->id();
            $table->string('concept');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepts');
    }
};
