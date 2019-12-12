<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Product;
use Faker\Generator as Faker;

$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->catchPhrase,
        'price' => $faker->randomFloat(2, 10, 1000),
    ];
});
