<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreaConfig extends Model
{
    protected $table = 'areas_config';
    protected $primaryKey = 'id_area';
    public $incrementing = false;
	protected $fillable = [
        'id_secretaria',
        'id_direccion',
        'id_equipo',
        'campo_descripcion'
    ];

    public function datos_complementarios()
    {
        return $this->belongsToMany('\App\DatosComplementarios', 'area_dato', 'area_id', 'dato_id')->orderBy('id', 'asc');
    }

    public function equipo_area()
    {
        return $this->belongsToMany('\App\EquipoArea', 'equipo_area', 'id_area', 'id_equipo');
    }

    public function equipos(){
        return $this->belongsToMany('\App\EquipoArea')
            ->withPivot('descripcion','estado');
    }

    public function area()
    {
        return $this->belongsTo('App\Tag','id_area','id_tag');
    }
}
