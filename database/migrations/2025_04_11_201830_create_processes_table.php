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

        Schema::create('processes', function (Blueprint $table) {
            $table->id();
            $table->string('requirement');
            $table->tinyInteger('state');
            $table->longText('comment');
            $table->foreignId('transaction_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('stage_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->boolean('completed');
            $table->dateTime('delivery_date')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('processes');
    }
};
