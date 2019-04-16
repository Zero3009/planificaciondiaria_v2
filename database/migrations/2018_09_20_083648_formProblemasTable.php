<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FormProblemasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problemas_form',function(Blueprint $table){
            $table->increments('id');
            $table->integer('nro_form')->unique();
            $table->date('fecha');
            $table->integer('nro_sua')->nullable();
            $table->integer('anio_sua')->nullable();
            $table->string('direccion');
            $table->string('coordenada_x');
            $table->string('coordenada_y');
            $table->string('especie')->nullable();
            $table->double('distancia_ref', 15,2)->nullable();
            $table->string('uno_terceros')->nullable();
            $table->string('dos_cables')->nullable();
            $table->string('tres_no_coincide')->nullable();
            $table->string('cuatro_no_existe')->nullable();
            $table->string('cinco_transito')->nullable();
            $table->string('seis_vectores')->nullable();
            $table->string('vect_otro_text')->nullable();
            $table->string('siete_vecino_niega')->nullable();
            $table->string('observaciones')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('problemas_form');
    }
}
