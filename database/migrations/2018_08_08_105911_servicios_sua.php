<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ServiciosSua extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servicios_sua', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_solicitud')->nullable();
            $table->integer('nro');
            $table->integer('anio');
            $table->string('leyenda');
            $table->string('fecha_intervencion');
            $table->string('tipo_resolucion');
            $table->string('estado');
            $table->string('url_error')->nullable();
            $table->string('servicio_tipo');
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
        Schema::dropIfExists('servicios_sua');
    }
}
