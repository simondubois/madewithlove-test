<?php

use App\Cart;
use Illuminate\Database\Seeder;

// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
class CartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Cart::class, 1000)->create();
    }
}
