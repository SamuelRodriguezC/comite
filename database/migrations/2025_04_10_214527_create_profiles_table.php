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

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->bigInteger('document_number');
            $table->bigInteger('phone_number');
            $table->tinyInteger('level');
            /**
             * Ensures referential integrity during record updates and deletions
             */
            $table->foreignId('document_id')
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
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['document_id']);
        });
        Schema::dropIfExists('profiles');
    }
};
