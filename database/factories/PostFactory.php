<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\User;

$factory->define(App\Models\Post::class, function (Faker $faker) {//Definimos la ubicacion del modelo.
    $count = User::count();//Para saber la cantidad de usuarios y asi el id limite que va en autoincremento y asignar dinamicamente un usuraio a los posts con la funcion faker
    //faker nos ayudara a generar datos para llenar los campos
    return [
        'title' => $faker->name,
        'content' => $faker->text($maxNbChars=50),
        'user_id' => $faker->numberBetween(1, $count),
    ];
});
