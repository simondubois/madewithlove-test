<?php

use Illuminate\Database\Seeder;

// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProductsTableSeeder::class);
        $this->call(CartsTableSeeder::class);
        $this->call(CartProductsTableSeeder::class);
    }
}
