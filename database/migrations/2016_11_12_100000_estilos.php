<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Estilos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estilos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_area')->unsigned();
            $table->foreign('id_area')->references('id_tag')->on('tags');
            $table->string('descripcion', 60);
            $table->string('iconUrl', 60)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('opacity', 5, 2)->nullable();
            $table->string('color', 60)->nullable();
            $table->string('dashArray', 60)->nullable();
            $table->decimal('fillOpacity', 5, 2)->nullable();
            $table->string('fillColor', 60)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('estilos');
    }
}
