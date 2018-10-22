<?php

use Faker\Generator as Faker;
$factory->define(App\Reservation::class, function (Faker $faker) {
    $users = App\User::pluck('id')->toArray();
    return [
        'reservation_date' => date("Y-m-d"),
        'people_number' => $faker->numberBetween(1,3),
        'user_id' => $faker->randomElement($users)
    ];
});
