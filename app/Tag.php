<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';
	protected $primaryKey = 'id_tag';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['desc', 'grupo', 'estado'];
    public $timestamps = false;

    public function estilo()
    {
        return $this->hasOne('App\Estilo', 'id_area');
    }

    public function datos_complementarios()
    {
    	return $this->belongsToMany('\App\DatosComplementarios', 'area_dato', 'area_id', 'dato_id')->orderBy('id', 'asc');
    }
}
