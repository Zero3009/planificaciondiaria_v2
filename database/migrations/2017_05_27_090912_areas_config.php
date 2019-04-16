<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AreasConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas_config', function (Blueprint $table) {

            $table->integer('id_area')->unsigned()->unique();
            $table->foreign('id_area')->references('id_tag')->on('tags');
            $table->integer('id_secretaria')->unsigned();
            $table->foreign('id_secretaria')->references('id_tag')->on('tags');
            $table->integer('id_direccion')->unsigned();
            $table->foreign('id_direccion')->references('id_tag')->on('tags');

            $table->mediumText('campo_descripcion')->nullable();
            $table->boolean('estado')->default(true);

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
        Schema::dropIfExists('areas_config');
    }
}
