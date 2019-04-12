<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProblemasForm extends Model
{
	protected $table = 'problemas_form';
	protected $primaryKey = 'id';
	protected $fillable = [
		'nro_form',
		'fecha',
		'nro_sua',
		'anio_sua',
		'direccion',
		'coordenada_x',
		'coordenada_y',
		'especie',
		'distancia_ref',
		'uno_terceros',
		'dos_cables',
		'tres_no_coincide',
		'cuatro_no_existe',
		'cinco_transito',
		'seis_vectores',
		'vect_otro_text',
		'siete_vecino_niega',
		'observaciones',
	];
	public $timestamps = false;

	public function interven()
    {
        return $this->hasMany('App\Intervenciones','form_id');
    }
    /*public function intervenOne()
    {
    	$query = $this->hasOne('App\Intervenciones','form_id');
        return $query->latest()->first();
    }
    public function uno()
    {
    	return $this->interven()->first();
    }*/
}