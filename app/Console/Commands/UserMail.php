<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
//importamos
use Illuminate\Support\Facades\Mail;
use App\Mail\UserWelcome;
use App\User;

class UserMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'command:name';
    protected $signature = 'user:mail {id : Represent user id} { --flag : Condicional}';//Definimos la estructura de nuestro comando, donde le pasamos un parametro(id) y flags({--name=emaildestiny}) con valores por defecto. despues de los : se pone una descripcion

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email to an user';//Descripcion de lo que hace

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()//Aqui definiremos todo el codigo que hara nuestro comando 
    {
        
        //obtenemos el usuario que le esta enviando como parametro del comando mediante el metodo argument
        $user = User::find($this->argument('id'));
        //hacemos una condicion para comprobar si existe el usuario
        if($user) {
            //para obtener los opciones, como son los flags, de nuestro comando usamos
            $option = $this->option('flag');
            echo $option."\n";
            Mail::to($user->email)->send(new UserWelcome($user));//
            echo "Email send successfully \n";
        } else {
            echo "El usuario no existe \n";
        }
    }
}
