<?php

use Illuminate\Database\Seeder;
//Primero importamos nuestro modelo
use \App\User;

//Para poder ejecutar el seeder debemos registrarlo en DAtabaseSeeder
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Esto lo estamos haciendo de forma manual, sin embargo laravel provee de una libreria faker y los factories 
        // User::create(["name"=>"luis",  "email"=>"luis@gmail.com", "password"=>"askj@#s4ls"]);
        // User::create(["name"=>"ruso",  "email"=>"ruso@gmail.com", "password"=>"askj@#s4ls"]);

        //Para usar los factories usamo lo que sigue
        factory(User::class)->times(40)->create();//Despues podemos ejecutar 'php artisan db:seed'
    }
}
