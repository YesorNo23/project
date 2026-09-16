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
        Schema::create('migrations', function (Blueprint $table) {
            // int(10) UNSIGNED AUTO_INCREMENT PRIMARY KEY
            $table->increments('id'); 
            
            $table->string('migration', 255);
            $table->integer('batch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('migrations');
    }
};