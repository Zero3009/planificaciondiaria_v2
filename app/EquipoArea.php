<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EquipoArea extends Model
{
    protected $table = 'equipos';
    protected $primaryKey = 'id_equipo';
    //Definimos los campos que se pueden llenar con asignación masiva
    protected $fillable = ['descripcion', 'estado'];
}
