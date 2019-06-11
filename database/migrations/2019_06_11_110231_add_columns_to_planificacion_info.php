<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToPlanificacionInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE planificacion_info ADD COLUMN geom geography(geometry,4326);');

        Schema::table('planificacion_info', function (Blueprint $table) {
            $table->integer('estilo_id')->unsigned()->nullable();
            $table->foreign('estilo_id')->references('id')->on('estilos');
        });

        DB::table('planificacion_info')
            ->join('points as puntos', 'puntos.id_info', '=', 'planificacion_info.id_info')
            ->update([ 
                'geom' => DB::raw("puntos.geom"),
                'estilo_id' => DB::raw("puntos.estilo"),
            ]);

        DB::table('planificacion_info')
            ->join('polygons as poligonos', 'poligonos.id_info', '=', 'planificacion_info.id_info')
            ->update([ 
                'geom' => DB::raw("poligonos.geom"),
                'estilo_id' => DB::raw("poligonos.estilo"),
            ]);

        DB::table('planificacion_info')
            ->join('linestrings as lineas', 'lineas.id_info', '=', 'planificacion_info.id_info')
            ->update([ 
                'geom' => DB::raw("lineas.geom"),
                'estilo_id' => DB::raw("lineas.estilo"),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('planificacion_info', function (Blueprint $table) {
            $table->dropColumn('estilo_id');
            $table->dropColumn('geom');
        });
    }
}
