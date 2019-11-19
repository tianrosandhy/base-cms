<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PostCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->text('seo')->nullable();
            $table->tinyinteger('is_active')->nullable();
            $table->timestamps();
        });

        Schema::create('post_to_categories', function(Blueprint $table){
        	$table->increments('id');
        	$table->integer('post_id')->nullable();
        	$table->integer('post_category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_categories');
        Schema::dropIfExists('post_to_categories');
    }
}
