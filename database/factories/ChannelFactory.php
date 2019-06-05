<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Channel;
use Faker\Generator as Faker;

$factory->define(Channel::class, function (Faker $faker) {
    $name = $faker->word.time();

    return [
        'name' => $name,
        'slug' => $name,
    ];
});
