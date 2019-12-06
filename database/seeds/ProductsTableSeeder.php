<?php

use App\Product;
use Illuminate\Database\Seeder;

// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        collect(json_decode(Storage::get('products.json'), true))
            ->mapInto(Product::class)
            ->each->save();
    }
}
