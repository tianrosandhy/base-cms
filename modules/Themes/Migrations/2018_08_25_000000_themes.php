<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Themes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes_option', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('type')->default(1); // default text => [1=>'text',2=>'textarea',3=>'image',4=>'select',5=>'checkbox']
            $table->text('default')->nullable();
            $table->text('value')->nullable();
            $table->text('options')->nullable();
            $table->integer('is_required')->default(0);
            $table->string('theme');
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
        Schema::dropIfExists('themes_option');
    }
}
