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
        Schema::create('playlist_detail', function (Blueprint $table) {
            // id int(11) AUTO_INCREMENT PRIMARY KEY
            $table->integer('id', true);

            // playlistid และ musicid ต้องเป็น int(11) และทำ Index
            $table->integer('playlistid')->nullable()->index();
            $table->integer('musicid')->nullable()->index();
            
            $table->integer('playlist_order')->nullable();
            $table->dateTime('add_date')->nullable();
            $table->tinyInteger('status')->nullable();

            // Foreign Key Constraints (เรียงลำดับอ้างอิงตาม SQL ต้นฉบับ)
            $table->foreign('musicid', 'playlist_detail_ibfk_1')
                  ->references('id')->on('music')
                  ->onDelete('no action')
                  ->onUpdate('no action');

            $table->foreign('playlistid', 'playlist_detail_ibfk_2')
                  ->references('id')->on('playlist')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบ Foreign Key ก่อนดรอปตาราง
        Schema::table('playlist_detail', function (Blueprint $table) {
            $table->dropForeign('playlist_detail_ibfk_1');
            $table->dropForeign('playlist_detail_ibfk_2');
        });

        Schema::dropIfExists('playlist_detail');
    }
};