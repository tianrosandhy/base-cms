<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Media extends Migration
{
    public function up()
    {
        //struktur migrate dari database lama nggak dipakai.
        Schema::dropIfExists('medias');
        Schema::create('medias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('path')->nullable();
            $table->string('basename')->nullable();
            $table->string('extension')->nullable();
            $table->string('mimetypes')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('medias');
    }
}
