<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserWelcome extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('homestead@gmail.com')//definimos de que correo(ficticio) se envira
            ->view('mails.welcome')
            ->with([//Mando estas propiedades a la vista
                'user' => $this->user
            ]);//Exiten mas metodos como attach para adjunar archivos
    }
}
