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
        Schema::disableForeignKeyConstraints();

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('component');
            /**
             * Ensures referential integrity during record updates and deletions
             */
            $table->foreignId('option_id')
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /**
         * Unlinks the foreign key before deleting the table
         */
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['option_id']);
        });
        Schema::dropIfExists('transactions');
    }
};
