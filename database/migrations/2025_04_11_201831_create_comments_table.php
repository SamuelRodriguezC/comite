<?php

use App\Models\Profile;
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

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->longText('comment');
            $table->foreignId('process_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignId('concept_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // ----------- Mostrar Relación -----------
            // $table->unsignedBigInteger('profile_id');
            // $table->foreign('profile_id')
            //         ->references('id')
            //         ->on('profiles')
            //         ->onDelete('cascade')
            //         ->onUpdate('cascade');

            $table->foreignIdFor(Profile::class);
            $table->timestamps();
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Revertir las migraciones.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
