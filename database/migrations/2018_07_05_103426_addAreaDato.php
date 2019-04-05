<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAreaDato extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('area_dato', function(Blueprint $table) {
            $table->integer('area_id')->unsigned();
            $table->integer('dato_id')->unsigned();
            $table->primary(['area_id','dato_id']);
            $table->foreign('area_id')->references('id_tag')->on('tags');
            $table->foreign('dato_id')->references('id')->on('datos_complementarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('area_dato');
    }
}
