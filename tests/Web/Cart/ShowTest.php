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
class ShowTest extends TestCase
{
    use DatabaseMigrations;

    public function test new cart()
    {
        // given
        //

        // when
        $response = $this->json('GET', 'cart/show');

        // then
        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'email' => null,
                'name' => null,
                'payment_method' => null,
                'total_price' => 0.0,
                'paid_at' => null,
                'available_payment_methods' => ['deus_ex', 'elder_scrolls', 'fallout'],
                'cartProducts' => [],
            ],
        ]);
    }

    public function test existing cart()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Product::class)->create(['name' => 'BBB', 'price' => 678.90]);
        factory(Cart::class)->create([
            'email' => 'thomas.anderson@metacortex.com',
            'name' => 'Thomas Anderson',
            'payment_method' => 'deus_ex',
            'total_price' => 1234.5,
            'paid_at' => null
        ]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 10]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 2, 'quantity' => 20])->delete();
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('GET', 'cart/show');

        // then
        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'email' => 'thomas.anderson@metacortex.com',
                'name' => 'Thomas Anderson',
                'payment_method' => 'deus_ex',
                'paid_at' => null,
                'total_price' => 1234.5,
                'available_payment_methods' => ['deus_ex', 'elder_scrolls', 'fallout'],
                'cartProducts' => [
                    [
                        'id' => 1,
                        'product_id' => 1,
                        'product_name' => 'AAA',
                        'product_price' => 123.45,
                        'total_price' => 1234.5,
                        'quantity' => 10,
                    ],
                ],
            ],
        ]);
    }
}
