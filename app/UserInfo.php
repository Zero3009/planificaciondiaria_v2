<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'users_info';
    protected $primaryKey = 'id_user';
	protected $fillable = [
        'id_area',
        'id_user',
        'estado'
    ];
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function area()
    {
        return $this->belongsTo('App\Tag', 'id_area', 'id_tag');
    }
}
