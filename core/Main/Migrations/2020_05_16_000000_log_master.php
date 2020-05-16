<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LogMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //struktur migrate dari database lama nggak dipakai.
        Schema::create('log_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->text('url')->nullable();
            $table->string('type')->nullable(); //notice, warning, fatal error, dsb
            $table->text('description')->nullable();
            $table->text('file_path')->nullable();
            $table->text('backtrace')->nullable();
            $table->tinyinteger('is_reported')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_masters');
    }
}
