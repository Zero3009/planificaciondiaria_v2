<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosComplementarios extends Model
{
    protected $table = 'datos_complementarios';
    protected $primaryKey = 'id';
	protected $fillable = [
        'desc_corta',
        'desc_larga',
        'html'
    ];
    public $timestamps = false;

    public function datos_complementarios()
    {
        return $this->belongsToMany('\App\AreaConfig', 'area_dato', 'area_id', 'dato_id')->orderBy('id', 'asc');
    }
}