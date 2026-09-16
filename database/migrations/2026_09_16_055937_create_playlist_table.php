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
        Schema::create('playlist', function (Blueprint $table) {
            // int(11) AUTO_INCREMENT PRIMARY KEY
            $table->integer('id', true);

            // int(11) DEFAULT NULL พร้อมทำ Index
            $table->integer('uid')->nullable()->index();
            
            $table->string('name', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->mediumText('detail')->nullable();
            
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            
            $table->tinyInteger('status')->nullable();

            // Foreign Key Constraint
            $table->foreign('uid', 'playlist_ibfk_1')
                  ->references('id')->on('user')
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
        Schema::table('playlist', function (Blueprint $table) {
            $table->dropForeign('playlist_ibfk_1');
        });

        Schema::dropIfExists('playlist');
    }
};