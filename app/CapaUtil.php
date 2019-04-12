<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CapaUtil extends Model
{
    protected $table = 'capas_utiles';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['nombre', 'iconUrl', 'weight', 'opacity', 'color', 'dashArray', 'fillOpacity', 'fillColor'];
    public $timestamps = false;
}
