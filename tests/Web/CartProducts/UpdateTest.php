<?php

namespace Tests\Web\CartProducts;

use App\Cart;
use App\CartProduct;
use App\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class UpdateTest extends TestCase
{
    use DatabaseMigrations;

    public function test mising cart product id()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('PUT', 'cart_products', ['quantity' => 10]);

        // then
        $response->assertStatus(405); // "Method Not Allowed" is thrown as POST request `to cart_products` is permitted.
    }

    public function test mising quantity()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('PUT', 'cart_products/1');

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['quantity']);
    }

    public function test invalid product id()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('PUT', 'cart_products/2', ['quantity' => 10]);

        // then
        $response->assertNotFound();
    }

    public function test negative quantity()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('PUT', 'cart_products/1', ['quantity' => -1]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['quantity']);
    }

    public function test unrelated cart product id()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 2, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('PUT', 'cart_products/1', ['quantity' => 10]);

        // then
        $response->assertForbidden();
        $response->assertExactJson(['message' => 'UNRELATED_CART_PRODUCT']);
    }

    public function test strictly positive quantity()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('PUT', 'cart_products/1', ['quantity' => 10]);

        // then
        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'id' => 1,
                'product_id' => 1,
                'product_name' => 'AAA',
                'product_price' => 123.45,
                'total_price' => 1234.5,
                'quantity' => 10,
            ],
        ]);
    }
}
