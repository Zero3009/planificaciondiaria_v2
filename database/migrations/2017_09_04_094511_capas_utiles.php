<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CapasUtiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capas_utiles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre', 60 );
            $table->json('geojson');
            $table->string('iconUrl', 60)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->decimal('opacity', 5, 2)->nullable();
            $table->string('color', 60)->nullable();
            $table->string('dashArray', 60)->nullable();
            $table->decimal('fillOpacity', 5, 2)->nullable();
            $table->string('fillColor', 60)->nullable();
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
        Schema::drop('capas_utiles');
    }
}
