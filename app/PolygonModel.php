<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Phaza\LaravelPostgis\Eloquent\PostgisTrait;
use Phaza\LaravelPostgis\Geometries\Point;

class PolygonModel extends Model
{
    use PostgisTrait;

    protected $table = 'polygons';
	protected $primaryKey = 'id_info';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['estilo'];
    protected $postgisFields = ['geom'];
    public $timestamps = false;
}