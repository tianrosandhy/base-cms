<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Settings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();
        Schema::create('settings_structure', function (Blueprint $table) {
            $table->increments('id');
            $table->string('param');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('default_value')->nullable();
            $table->string('type')->nullable();
            $table->string('group')->nullable();
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
        Schema::dropIfExists('settings_structure');
    }
}
