<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\CartProduct;
use App\Product;
use Faker\Generator as Faker;

$factory->define(CartProduct::class, function (Faker $faker, array $data) {
    return [
        'product_id' => $data['product_id'],
        'product_name' => Product::find($data['product_id'])->name,
        'product_price' => Product::find($data['product_id'])->price,
        'quantity' => $faker->numberBetween(1, 20),
        'total_price' => function (array $attributes): float {
            return $attributes['product_price'] * $attributes['quantity'];
        },
    ];
});
