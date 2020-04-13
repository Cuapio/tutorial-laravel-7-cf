<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //Ya que laravel al crear eventos genera la carpeta Events usamos la ruta del namespace
        'App\Events\UserRegistered' => [//Representa al evento principal en el cual contiene un array con referencias a las clases de todos los listeners
            'App\Listeners\SendWelcomeEmail', //Es recomendable separar cada accion en listeners diferentes, pueden ser varios
        ],//Despues debemos de ejecutar php artisan event:generate para generar los archivos de eventos en app/Events/UserRegistered.php y listeners indicados en el array en la carpeta app/Listeners/SendWelcomeEmail.php
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
