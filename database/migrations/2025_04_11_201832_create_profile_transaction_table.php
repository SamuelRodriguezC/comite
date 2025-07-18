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

        Schema::create('profile_transaction', function (Blueprint $table) {
            $table->foreignId('profile_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('transaction_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('courses_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            //$table->unsignedBigInteger('role_id')
            //    ->nullable();
            //$table->foreign('role_id')
            //    ->references('id')
            //    ->on('roles');
            $table->foreignId('role_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_transaction');
    }
};
