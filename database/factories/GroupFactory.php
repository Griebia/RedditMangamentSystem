<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\Group::class, function (Faker $faker) {
    $users = App\User::all()->pluck('id')->toArray();
    return [
        'info' => $faker->text(50),
        'user_id' => $faker->randomElement($users)
    ];
});
