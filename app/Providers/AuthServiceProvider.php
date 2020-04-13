<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\PostPolicy;
use App\Models\Post;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [//Aqui registramos nuestra Politica para ello
        // 'App\Model' => 'App\Policies\ModelPolicy',//utilizamos el nombre del modelo(referencia a la clase Post) y la referencia a la clase de la politica, pero, una vez importando las clases podemos hacer:
        Post::class => PostPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->deleteAction();
        //
    }

    //Primero 
    public function deleteAction() {//Esto es lo que tenemos que registrar en el metodo boot
        //Aqui voy a definir mi Gate
        Gate::define('delete-post', function($user, $post) {//Como primer parametro le paso el nombre de mi gate, luego un closure que recibe al usuario autenticado(que se pasa automaicamente) y el recurso a evaluar
            return $user->id == $post->id;//Aseuramos si la persona que desea eliminar el post es el mismo ppropietario
        });
    }
}
