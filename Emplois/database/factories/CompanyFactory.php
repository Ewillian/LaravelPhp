<?php

use Faker\Generator as Faker;
use App\Company;

$factory->define(Company::class, function (Faker $faker) {
    return [
        'Company_name' => $faker->domainName,
    ];
});
