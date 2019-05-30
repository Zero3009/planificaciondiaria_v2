<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanificacionInfo extends Model
{
    protected $table = 'planificacion_info';
	protected $primaryKey = 'id_info';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['id_area', 'descripcion', 'id_tipo_trabajo', 'horario', 'callezona', 'id_corte_calzada', 'tipo_geometria', 'id_usuario', 'estado', 'fecha_planificada','datos_complementarios','progreso'];

    // MUTATORS
    protected $casts = [
        'datos_complementarios' => 'array'
    ];

	public function setDescripcionAttribute($value)
	{
	    $this->attributes['descripcion'] = strtoupper($value);
	}

	public function setCallezonaAttribute($value)
	{
	    $this->attributes['callezona'] = strtoupper($value);
	}

	public function tipo_trabajo()
    {
        return $this->belongsTo('App\Tag','id_tipo_trabajo','id_tag');
    }

    public function corte_calzada()
    {
        return $this->belongsTo('App\Tag','id_corte_calzada','id_tag');
    }

    public function area()
    {
        return $this->belongsTo('App\Tag','id_area','id_tag');
    }
}
