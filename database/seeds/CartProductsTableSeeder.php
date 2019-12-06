<?php

use App\Cart;
use App\CartProduct;
use App\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
class CartProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // get all carts
        Cart::all()
            // combine each cart with a random set of products
            ->flatMap(function (Cart $cart): Collection {
                return collect([$cart])->crossJoin(
                    Product::all()->random(rand(1, Product::count()))
                );
            })
            // create a cart product record for each cart-product combination
            ->eachSpread(function (Cart $cart, Product $product): void {
                factory(CartProduct::class)->create(['cart_id' => $cart->id, 'product_id' => $product->id]);
            });

        // get all unpaid carts
        Cart::whereNull('paid_at')->with('cartProducts')->get()
            // pick some cart products to delete
            ->flatMap(function (Cart $cart): Collection {
                return $cart->cartProducts->random(rand(0, $cart->cartProducts->count()));
            })
            // delete selected cart products
            ->each->delete();

        // get all paid carts having more than one cart product
        Cart::whereNotNull('paid_at')->has('cartProducts', '>', 1)->with('cartProducts')->get()
            // pick some cart products to delete
            ->flatMap(function (Cart $cart): Collection {
                return $cart->cartProducts->random(rand(1, $cart->cartProducts->count() - 1));
            })
            // delete selected cart products
            ->each->delete();

        Cart::with('cartProducts')->get()->each(function (Cart $cart): void {
            $cart->total_price = $cart->cartProducts->sum('total_price');
            $cart->save();
        });
    }
}
