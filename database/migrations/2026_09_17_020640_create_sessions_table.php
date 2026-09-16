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
        Schema::create('sessions', function (Blueprint $table) {
            // varchar(255) NOT NULL PRIMARY KEY
            $table->string('id', 255)->primary();

            // bigint(20) UNSIGNED DEFAULT NULL พร้อมทำ Index
            $table->unsignedBigInteger('user_id')->nullable()->index('sessions_user_id_index');
            
            // varchar(45) DEFAULT NULL
            $table->string('ip_address', 45)->nullable();
            
            // text DEFAULT NULL
            $table->text('user_agent')->nullable();
            
            // longtext NOT NULL
            $table->longText('payload');
            
            // int(11) NOT NULL พร้อมทำ Index
            $table->integer('last_activity')->index('sessions_last_activity_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};