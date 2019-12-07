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
class StoreTest extends TestCase
{
    use DatabaseMigrations;

    public function test mising product id()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);

        // when
        $response = $this->json('POST', 'cart_products', ['quantity' => 10]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['product_id']);
    }

    public function test mising quantity()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);

        // when
        $response = $this->json('POST', 'cart_products', ['product_id' => 1]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['quantity']);
    }

    public function test invalid product id()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);

        // when
        $response = $this->json('POST', 'cart_products', ['product_id' => 2, 'quantity' => 10]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['product_id']);
    }

    public function test invalid quantity()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);

        // when
        $response = $this->json('POST', 'cart_products', ['product_id' => 1, 'quantity' => 0]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['quantity']);
    }

    public function test valid request()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);

        // when
        $response = $this->json('POST', 'cart_products', ['product_id' => 1, 'quantity' => 10]);

        // then
        $response->assertCreated();
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
