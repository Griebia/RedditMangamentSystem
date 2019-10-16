<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    $users = App\RUser::all()->pluck('id')->toArray();
    return [
        'url' => $faker->text(50),
        'title' => $faker->text(50),
        'sr' => $faker->text(50),
        'kind' => $faker->text(50),
        'postTime' => $faker->dateTime(),
        'ruser_id' => $faker->randomElement($users)
    ];
});
