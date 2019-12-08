<?php

namespace Tests\Web\Cart;

use App\Cart;
use App\CartProduct;
use App\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class PayTest extends TestCase
{
    use DatabaseMigrations;

    public function test mising email()
    {
        // given
        //

        // when
        $response = $this->json('PUT', 'cart/pay', [
            'name' => 'Thomas Anderson',
            'payment_method' => 'deus_ex',
        ]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test mising name()
    {
        // given
        //

        // when
        $response = $this->json('PUT', 'cart/pay', [
            'email' => 'thomas.anderson@metacortex.com',
            'payment_method' => 'deus_ex',
        ]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test mising payment method()
    {
        // given
        //

        // when
        $response = $this->json('PUT', 'cart/pay', [
            'email' => 'thomas.anderson@metacortex.com',
            'name' => 'Thomas Anderson',
        ]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payment_method']);
    }

    public function test invalid email()
    {
        // given
        //

        // when
        $response = $this->json('PUT', 'cart/pay', [
            'email' => 'invalid_email',
            'name' => 'Thomas Anderson',
            'payment_method' => 'deus_ex',
        ]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }

    public function test invalid name()
    {
        // given
        //

        // when
        $response = $this->json('PUT', 'cart/pay', [
            'email' => 'thomas.anderson@metacortex.com',
            'name' => str_repeat('å', 256),
            'payment_method' => 'deus_ex',
        ]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test invalid payment method()
    {
        // given
        //

        // when
        $response = $this->json('PUT', 'cart/pay', [
            'email' => 'thomas.anderson@metacortex.com',
            'name' => 'Thomas Anderson',
            'payment_method' => 'invalid_payment_method',
        ]);

        // then
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['payment_method']);
    }

    public function test new cart()
    {
        // given
        //

        // when
        $response = $this->json('PUT', 'cart/pay', [
            'email' => 'thomas.anderson@metacortex.com',
            'name' => 'Thomas Anderson',
            'payment_method' => 'deus_ex',
        ]);

        // then
        $response->assertStatus(422);
        $response->assertExactJson(['message' => 'EMPTY_CART']);
    }

    public function test filled cart()
    {
        // given
        Carbon::setTestNow(Carbon::now());
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['total_price' => 1234.5, 'paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 10]);
        $this->session(['cart_id' => 1]);

        // when
        $response = $this->json('PUT', 'cart/pay', [
            'email' => 'thomas.anderson@metacortex.com',
            'name' => 'Thomas Anderson',
            'payment_method' => 'deus_ex',
        ]);

        // then
        $response->assertOk();
        $response->assertExactJson([
            'data' => [
                'email' => 'thomas.anderson@metacortex.com',
                'name' => 'Thomas Anderson',
                'payment_method' => 'deus_ex',
                'paid_at' => Carbon::now()->toDateTimeString(),
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
        Carbon::setTestNow();
    }
}
