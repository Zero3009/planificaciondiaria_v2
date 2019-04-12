<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicioSua extends Model
{
    protected $table = 'servicios_sua';

	protected $fillable = [
        'id_solicitud',
        'nro',
        'anio',
        'leyenda',
        'fecha_intervencion',
        'tipo_resolucion',
        'estado',
        'servicio_tipo',
        'url_error'
    ];

}
