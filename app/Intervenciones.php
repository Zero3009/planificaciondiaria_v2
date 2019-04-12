<?php

namespace App;

use Illuminate\Database\Eloquent\Model;	

class Intervenciones extends Model
{
	protected $table = 'intervenciones';
	protected $primaryKey = 'id';
	protected $fillable = [
		'form_id',
		'tipo_intervencion',
		'observaciones',
		'fecha',
		'notificado'
	];
	public function problemform()
    {
        return $this->belongsTo('App\ProblemasForm');
    }
}