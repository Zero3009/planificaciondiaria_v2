<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Estilo extends Model
{
    protected $table = 'estilos';
	protected $primaryKey = 'id';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['descripcion', 'iconUrl', 'weight', 'opacity', 'color', 'dashArray', 'fillOpacity', 'fillColor', 'id_area'];
    public $timestamps = false;

    public function area()
    {
        return $this->belongsTo('App\Tag', 'id_tag');
    }
}
