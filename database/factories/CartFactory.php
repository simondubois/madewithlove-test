<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Cart;
use Faker\Generator as Faker;

$factory->define(Cart::class, function (Faker $faker) {
    return [
        'name' => $faker->boolean ? $faker->name : null,
        'email' => $faker->boolean ? $faker->email : null,
        'payment_method' => $faker->boolean ? $faker->randomElement(['deus_ex', 'elder_scrolls', 'fallout']) : null,
        'created_at' => $faker->dateTimeBetween('-1 year'),
        'paid_at' => function (array $attributes) use ($faker) {
            return $faker->boolean ? $faker->dateTimeBetween($attributes['created_at']) : null;
        },
    ];
});
