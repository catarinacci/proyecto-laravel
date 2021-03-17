<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $table = 'images';

    //Relación One to Many / de uno a muchos
    public function comments(){
        return $this->hasMany('App\Comment')->orderBy('id','desc');
    }

    //Relación One to Many / de uno a muchos
    public function likes(){
        return $this->hasMany('App\Like');
    }

    //Relacion Many to One / de muchos a uno
    public function user(){
        return $this->belongsTo('App\User', 'user_id');
    }
}
