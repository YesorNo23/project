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
        Schema::create('cat_music', function (Blueprint $table) {
            // int(11) AUTO_INCREMENT PRIMARY KEY
            $table->integer('id', true);

            $table->integer('musicid')->nullable();
            $table->integer('catdetail_id')->nullable();
            $table->tinyInteger('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cat_music');
    }
};