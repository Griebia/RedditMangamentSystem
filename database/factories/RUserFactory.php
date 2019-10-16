<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(App\RUser::class, function (Faker $faker) {
    $users = App\User::all()->pluck('id')->toArray();
    return [
        'username' => $faker->name,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'token' => Str::random(10),
        'user_id' => $faker->randomElement($users)
    ];
});
