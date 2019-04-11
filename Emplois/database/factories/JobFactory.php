<?php

use Faker\Generator as Faker;
use App\Job;
$factory->define(Job::class, function (Faker $faker) {
    return [
        'titre' => $faker->sentence,
        'contenu' => $faker->paragraph,
    ];
});
