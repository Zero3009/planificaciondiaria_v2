<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;

class Geojson extends Model
{
	use PostgisTrait;

    protected $table = 'geojson';
	protected $primaryKey = 'id_geojson';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['area', 'descripcion', 'tipo_trabajo', 'horario', 'callezona', 'corte_calzada', 'id_usuario', 'estilo_point', 'estilo_polygon'];
    protected $postgisFields = ['point', 'polygon'];
}