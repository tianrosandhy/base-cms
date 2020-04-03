<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SlugMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //struktur migrate dari database lama nggak dipakai.
        Schema::create('slug_masters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('table')->nullable();
            $table->string('primary_key')->nullable(); //jaga2 kalo ada yg PK non integer
            $table->string('slug')->nullable();
            $table->string('language')->nullable();
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
        Schema::dropIfExists('slug_masters');
    }
}
