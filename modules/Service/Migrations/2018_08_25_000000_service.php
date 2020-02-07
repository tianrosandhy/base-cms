<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Service extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->integer('service_category_id')->nullable();
            $table->text('image')->nullable();
            $table->tinyinteger('is_active')->nullable();
            $table->timestamps();
        });

        Schema::create('service_categories', function(Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->tinyinteger('is_active')->nullable();
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
        Schema::dropIfExists('services');
        Schema::dropIfExists('service_categories');
    }
}
