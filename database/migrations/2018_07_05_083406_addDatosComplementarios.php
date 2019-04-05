<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDatosComplementarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_complementarios', function(Blueprint $table) {
            $table->increments('id');
            $table->string('desc_corta')->unique();
            $table->string('desc_larga');
            $table->string('html', 5000);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('datos_complementarios');
    }
}
