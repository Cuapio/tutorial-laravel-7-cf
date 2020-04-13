<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Este metodo se debe de definir inversamente en Post
    public function posts() {//Definimos nuestras relaciones que la tabla tendra, en este caso de uno a muchos con posts
        return $this->hasMany('App\Models\Post');//a hasMany le pasamos el modelo que representa a la tabla con la que tiene relacion User, en este caso Post con cardinalidad de uno a muchos 
        //***IMPORTANTE*** por convencion Laravel buscara la clave foranea con el nombre de nuestro modelo User(en minusculas) concatenado _id (osea, user_id igual que el nombre de nuesta lave foranea en Post(se encuentra en la migracion AddUserIdToPostsTable)). Por eso es importante llevar como gia estos estandares para no tener problemas cuando ejecuteos consutlas en Eloquent. Si en dado caso tenemos otro nombre que no siga la convencion DEBEMOS de pasar el nombre de la llave foranea como segundo parametro de hasMany por ejemplo, 
        //hasMany('App\Models\Post', 'user_id_fk'). 
        // Lo miso sucede si tu llave primaria en la tabla users no se llama id como convencion tambien, como tercer paraetro, debes de pasarselo a hasMany, por ejemplo 
        //hasMany('App\Models\Post', 'user_id_fk', 'user_id')
    }//En tinker podemos comprobarlo con User::find(1)->posts 

    //Y para terminar definiremos un metodo que no se usara pero para propositos demostrativos utilizando hasMany
    // public function userHasRoles() {
    //     return $this->belongsToMany('App\Models\Role', 'role_user', 'user_id');//A pesar de no exitir utilizamos belogsToMenay para indicar una relacion de muchos a muchos 
    // }//Este solo es para propositos demostrativos, pero en realidad no tiene uso

}
