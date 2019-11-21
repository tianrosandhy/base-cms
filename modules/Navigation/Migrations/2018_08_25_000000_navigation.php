<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Navigation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navigations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('group_name');
            $table->text('description')->nullable();
            $table->integer('max_level')->nullable();
            $table->tinyinteger('is_active')->nullable();
            $table->timestamps();
        });

        Schema::create('navigation_items', function($table){
            $table->increments('id');
            $table->integer('group_id');
            $table->string('title')->nullable();
            $table->string('type')->nullable();
            $table->text('url')->nullable();
            $table->string('route')->nullable();
            $table->string('slug')->nullable();
            $table->string('icon')->nullable();
            $table->tinyinteger('new_tab')->nullable();
            $table->integer('sort_no')->nullable();
            $table->integer('parent')->nullable();
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
        Schema::dropIfExists('navigations');
        Schema::dropIfExists('navigation_items');
    }
}
