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
        Schema::create('assessment_logs', function (Blueprint $table) {
            // id int(11) AUTO_INCREMENT PRIMARY KEY
            $table->integer('id', true);

            $table->string('q1',50)->nullable();
            $table->string('q2', 50)->nullable();
            $table->string('q3', 50)->nullable();
            $table->string('q4', 50)->nullable();
            $table->string('q5', 50)->nullable();
            $table->string('predicted_mood', 50)->nullable();
            $table->string('predicted_category', 50)->nullable();
            $table->decimal('confidence', 5 ,2)->nullable();
            $table->timestamp('created_at')->nullable();

           
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::dropIfExists('assessment_logs');
    }
};
