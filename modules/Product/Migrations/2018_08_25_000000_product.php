<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('image')->nullable();
            $table->string('url')->nullable();
            $table->text('seo')->nullable();
            $table->tinyinteger('is_active')->nullable();
            $table->timestamps();
        });

        Schema::create('product_to_categories', function(Blueprint $table){
            $table->increments('id');
            $table->integer('product_id')->nullable();
            $table->integer('category_id')->nullable();
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_to_categories');
    }
}
