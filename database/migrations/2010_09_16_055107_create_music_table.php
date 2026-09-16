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
        Schema::create('music', function (Blueprint $table) {
            // int(11) AUTO_INCREMENT PRIMARY KEY
            $table->integer('id', true);

            $table->string('name', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->mediumText('detail')->nullable();
            $table->integer('duration')->nullable();
            
            // ใช้คำสั่งนี้เพื่อให้ตรงกับ timestamp NULL DEFAULT NULL
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->string('file_path', 255)->nullable();
            $table->integer('play_count')->default(0);
            $table->tinyInteger('status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('music');
    }
};