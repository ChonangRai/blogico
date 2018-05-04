<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //Table Name
    protected $table='posts';
    //Primary Key
    public $primaryKey = 'id';

    public function user(){
    	return $this->belongsTo('App\User');
    
    }

    public function token_infos(){
    	return $this->hasMany('App\TokenInfo');
    }
}
