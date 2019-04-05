<?php

use Phaza\LaravelPostgis\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Polygons extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::create('polygons', function (Blueprint $table) {
            $table->integer('id_info')->unsigned();
            $table->foreign('id_info')->references('id_info')->on('planificacion_info');
            $table->polygon('geom');
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
        Schema::drop('polygons');
    }
}
