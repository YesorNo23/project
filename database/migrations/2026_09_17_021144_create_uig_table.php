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
        Schema::create('uig', function (Blueprint $table) {
            // id int(11) AUTO_INCREMENT PRIMARY KEY
            $table->integer('id', true);

            // uid และ ugid ต้องเป็น int(11) และทำ Index
            $table->integer('uid')->nullable()->index();
            $table->integer('ugid')->nullable()->index();
            
            $table->tinyInteger('status')->nullable();

            // Foreign Key Constraints
            $table->foreign('uid', 'uig_ibfk_1')
                  ->references('id')->on('user')
                  ->onDelete('no action')
                  ->onUpdate('no action');

            $table->foreign('ugid', 'uig_ibfk_2')
                  ->references('id')->on('usergroup')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ลบ Foreign Key ก่อนดรอปตารางเพื่อป้องกัน Error
        Schema::table('uig', function (Blueprint $table) {
            $table->dropForeign('uig_ibfk_1');
            $table->dropForeign('uig_ibfk_2');
        });

        Schema::dropIfExists('uig');
    }
};