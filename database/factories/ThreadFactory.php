<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Thread;
use Faker\Generator as Faker;

$factory->define(Thread::class, function (Faker $faker) {
    $title = $faker->sentence;

    return [
        'user_id' => function () {
            return factory(\App\User::class)->create()->id;
        },
        'channel_id' => function () {
            return factory(\App\Channel::class)->create()->id;
        },
        'title' => $title,
        'slug' => Str::slug($title),
        'body' => $faker->paragraph,
        'locked' => false,
    ];
});
