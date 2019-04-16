<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlanificacionInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planificacion_info', function (Blueprint $table) {

            $table->increments('id_info');

            $table->integer('id_area')->unsigned();
            $table->foreign('id_area')->references('id_tag')->on('tags');

            $table->string('descripcion', 255 );
            
            $table->integer('id_tipo_trabajo')->unsigned();
            $table->foreign('id_tipo_trabajo')->references('id_tag')->on('tags');

            $table->string('horario', 20 );
            $table->string('callezona', 255 );
                
            $table->integer('id_corte_calzada')->unsigned();
            $table->foreign('id_corte_calzada')->references('id_tag')->on('tags');

            $table->boolean('estado')->default(true);
            $table->string('tipo_geometria', 20 );

            $table->date('fecha_planificada')->nullable();

            $table->integer('id_usuario')->unsigned();
            $table->foreign('id_usuario')->references('id')->on('users');

            $table->json('datos_complementarios')->nullable();
            $table->string('progreso', 30)->default('planificado');
            
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
        Schema::dropIfExists('planificacion_info');
    }
}