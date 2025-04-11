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

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->string('concept');
            $table->longText('comment');
            /**
             * Ensures referential integrity during record updates and deletions
             */
            $table->foreignId('process_id')
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
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['process_id']);
        });
        Schema::dropIfExists('comments');
    }
};
