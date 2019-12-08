<?php

namespace Tests\Web\Cart;

use App\Cart;
use App\CartProduct;
use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class DestroyTest extends TestCase
{
    use DatabaseMigrations;

    public function test mising cart product id()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 10]);
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('DELETE', 'cart_products');

        // then
        $response->assertStatus(405); // "Method Not Allowed" is thrown as POST request `to cart_products` is permitted.
    }

    public function test unrelated cart product id()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 10]);

        // when
        $response = $this->json('DELETE', 'cart_products/1');

        // then
        $response->assertForbidden();
    }

    public function test valid request()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 10]);
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('DELETE', 'cart_products/1');

        // then
        $response->assertNoContent();
    }
}
