<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content', 'user_id', 'post_id'
    ];

    //definimos las relaciones
     public function post(){
         return $this->belongsTo('App\Models\Post');
     }//para comprobar $c = App\Models\Comment::find(1) y luego $c->post

     public function user() {
         return $this->belongsTo('App\User');
     }//para comprobar $c = App\Models\Comment::find(1) y luego $c->user
}
