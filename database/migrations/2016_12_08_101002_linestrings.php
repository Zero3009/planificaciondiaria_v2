<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Linestrings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('linestrings', function (Blueprint $table) {
            $table->integer('id_info')->unsigned();
            $table->foreign('id_info')->references('id_info')->on('planificacion_info');
            $table->linestring('geom');
            $table->integer('estilo')->unsigned()->nullable();
            $table->foreign('estilo')->references('id')->on('estilos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('linestrings');
    }
}
