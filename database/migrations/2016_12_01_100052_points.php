<?php

use Phaza\LaravelPostgis\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Points extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {    
        Schema::create('points', function (Blueprint $table) {
            $table->integer('id_info')->unsigned();
            $table->foreign('id_info')->references('id_info')->on('planificacion_info');
            $table->point('geom');
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
        Schema::drop('points');
    }
}
