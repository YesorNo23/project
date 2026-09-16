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
        Schema::create('history', function (Blueprint $table) {
            // int(11) AUTO_INCREMENT PRIMARY KEY
            $table->integer('id', true);

            $table->integer('uid')->nullable()->index();
            $table->integer('musicid')->nullable()->index();
            $table->integer('play_duration')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->tinyInteger('status')->nullable();

            // กำหนด Foreign Keys
            $table->foreign('uid', 'history_ibfk_1')
                  ->references('id')->on('user')
                  ->onDelete('no action')
                  ->onUpdate('no action');

            $table->foreign('musicid', 'history_ibfk_2')
                  ->references('id')->on('music')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบ Foreign Key ก่อนลบตารางเพื่อป้องกัน Error
        Schema::table('history', function (Blueprint $table) {
            $table->dropForeign('history_ibfk_1');
            $table->dropForeign('history_ibfk_2');
        });

        Schema::dropIfExists('history');
    }
};