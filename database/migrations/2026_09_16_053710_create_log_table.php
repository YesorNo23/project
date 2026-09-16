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
        Schema::create('log', function (Blueprint $table) {
            // id int(11) AUTO_INCREMENT PRIMARY KEY
            $table->integer('id', true);

            // uid int(11) DEFAULT NULL พร้อมทำ Index
            $table->integer('uid')->nullable()->index();
            
            // action, module, endpoint, ip_address
            $table->string('action', 50);
            $table->string('module', 50)->nullable();
            $table->string('endpoint', 255);
            $table->string('ip_address', 50);
            
            // date timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
            $table->timestamp('date')->useCurrent()->useCurrentOnUpdate();

            // Foreign Key Constraint
            $table->foreign('uid', 'log_ibfk_1')
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
        Schema::table('log', function (Blueprint $table) {
            $table->dropForeign('log_ibfk_1');
        });

        Schema::dropIfExists('log');
    }
};