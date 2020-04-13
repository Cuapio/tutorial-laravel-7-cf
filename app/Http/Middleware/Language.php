<?php

namespace App\Http\Middleware;

use Closure;
//Primero importamos el facede App el cal hace referencia a toda la aplicacion, lo cual no permitira tener acceso a todas las configuraciones que e encuentran en la carpeta config
use Illuminate\Support\Facades\App;

//se pretende que esta clase cambie de idioma de la app dependiendo del idioma del usuraio en su navegador 
class Language//Se encargara de modificar el idioma de acuerda a las caracteristicas del navegador del cliente
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)//le pasamos una referencia del objeto request el cual contendra la informacion del usuario que realiza la peticion, desde el cual podremos acceder al atributo que contenga el idioma del navegaor del usuario
    {//En este metodo definireos toda la logica de nuestro middleware
        $lang = $request->server('HTTP_ACCEPT_LANGUAGE');//server contiene la informacion del navegador del usuario y asi conoceremos dicho idioma
        $locale = substr($lang, 0, 2);//lo que nos devolvera los primeros caracteres que seran las iniciales del idioma como es o en 
        if($locale != 'en' && $locale != 'es') {//si cumple nuestra aplicacion no soporta dicho idiomas por lo cual asignamos una idioma por defecto
            $locale = 'en';
        } 
        //Llamamos al facade App
        App::setLocale($locale);
        return $next($request);//esta linea la implementa por defecto laravel para seguir con cada una de las peticiones que lleguen
    }
}

//Todos los middlewares se deben registrar en el archivo app/Http/Kernel.php en los arreglos de $middleware que se utiliza para las peticiones, y, en este caso tambien definimos el middleware en el arreglo $routeMiddleware para asignarle un nombre y poder utilizarlo desde una Route o un controlador
