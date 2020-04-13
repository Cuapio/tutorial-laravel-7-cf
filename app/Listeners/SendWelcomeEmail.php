<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcome;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)//Como se observa se le pasa una instancia del evento, de esta forma accedeos a sus propiedades
    {
        // Aqui ira la logica del listener
        $user = $event->user;
        //Es el email que se le va enviar con el metodo to al mail indicado y send paraenviarlo
        Mail::to($user->email)->send(new UserWelcome($user));//
    }
}
