<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'content', 'user_id',
    ];

    //Definimos las relaciones, ahora inversamento con User
    public function user() {//nota que usamos singular ya que sera relacion muchos a uno
        return $this->belongsTo('App\User');//de igual manera si la llave foranea no fuera user_id debemos de pasarle como segundo argumento el nombre del campo que pertenece a la llave foranea$this->belognsTo('App\User', 'user_id');
    }//para comprobar basta llamar en tinker App\Models\Post::find(1)->user

    public function comments() {
        return $this->hasMany('App\Models\Comment');
    }
}
