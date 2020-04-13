<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;//necesita heredar de 

class PaymentFacade extends Facade {
    //Tenemos que definir el metodo
    protected static function getFacadeAccessor()
    {
        return 'App\Models\Paypal';//retornamos la ruta del modelo a la cual registramos en el contenedor
    }
}