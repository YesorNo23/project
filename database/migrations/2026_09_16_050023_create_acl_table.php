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
        Schema::create('acl', function (Blueprint $table) {
            // สร้าง id เป็น int(11) AUTO_INCREMENT PRIMARY KEY
            $table->integer('id', true); 

            // สร้าง ugid และ appid เป็น int(11) ให้ตรงกับ id ของ usergroup และ application
            $table->integer('ugid')->nullable()->index();
            $table->integer('appid')->nullable()->index();
            
            $table->tinyInteger('acclevel')->nullable();
            $table->tinyInteger('status')->nullable();

            // Foreign Key Constraints
            $table->foreign('ugid', 'acl_ibfk_1')
                  ->references('id')->on('usergroup')
                  ->onDelete('no action')
                  ->onUpdate('no action');

            $table->foreign('appid', 'acl_ibfk_2')
                  ->references('id')->on('application')
                  ->onDelete('no action')
                  ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('acl', function (Blueprint $table) {
            $table->dropForeign('acl_ibfk_1');
            $table->dropForeign('acl_ibfk_2');
        });

        Schema::dropIfExists('acl');
    }
};