<?php

namespace Tests\Repositories\CartSessionRepository;

use App\Cart;
use App\Repositories\CartSessionRepository;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class CartTest extends TestCase
{
    use DatabaseMigrations;

    public function test cart id not in session()
    {
        // given
        factory(Cart::class)->create();

        // when
        $cart = app(CartSessionRepository::class)->cart();

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertFalse($cart->exists);
    }

    public function test cart id not in database()
    {
        // given
        factory(Cart::class)->create();
        $this->session(['cart_id' => 2]);

        // when
        $cart = app(CartSessionRepository::class)->cart();

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertFalse($cart->exists);
    }

    public function test cart id matching paid cart()
    {
        // given
        factory(Cart::class)->create(['paid_at' => Carbon::today()]);
        $this->session(['cart_id' => 1]);

        // when
        $cart = app(CartSessionRepository::class)->cart();

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertFalse($cart->exists);
    }

    public function test cart id matching unpaid cart()
    {
        // given
        factory(Cart::class)->create(['paid_at' => null]);
        $this->session(['cart_id' => 1]);

        // when
        $cart = app(CartSessionRepository::class)->cart();

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertTrue($cart->exists);
        $this->assertSame(1, $cart->id);
    }
}
