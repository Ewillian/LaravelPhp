<?php

use Faker\Generator as Faker;
use App\Post;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'titre' => $faker->sentence,
        'contenu' => $faker->paragraph,
    ];
});
