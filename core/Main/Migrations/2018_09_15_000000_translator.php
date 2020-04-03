<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Translator extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //struktur migrate dari database lama nggak dipakai.
        Schema::create('translator', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table');
            $table->string('field');
            $table->string('id_field');
            $table->string('lang');
            $table->text('content');
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
        Schema::dropIfExists('translator');
    }
}
