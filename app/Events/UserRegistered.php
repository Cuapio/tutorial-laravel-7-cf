<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserRegistered
{//Esta clase basicamente nos sirve como contenedor donde podemos almacenar variables(como $user). Este evento unicamente nos sirve para ejecutar todos los listenersque definimos en el EventServiceProvider, asi mismo obtener los valores del usuario cuando instancieos este metodo
    use Dispatchable, InteractsWithSockets, SerializesModels;

    //Por ejemplo definimos 
    public $user;//Podremos acceder a ella desde los listeners
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
