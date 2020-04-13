<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\Post;
use App\User;
use Faker\Generator as Faker;

$factory->define(App\Models\Comment::class, function (Faker $faker) {
    $countusers = User::count();
    $countposts = Post::count();
    return [
        'content' => $faker->text($maxNbChars=50),
        'user_id' => $faker->numberBetween(1, $countusers),
        'post_id' => $faker->numberBetween(1, $countposts),
    ];
});
