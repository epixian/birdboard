<?php

use Faker\Generator as Faker;

$factory->define(App\Project::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(1),
        'description' => $faker->sentence(3),
        'notes' => $faker->sentence(4),
        'owner_id' => factory(App\User::class)
    ];
});
