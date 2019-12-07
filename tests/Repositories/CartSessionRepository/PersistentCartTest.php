<?php

namespace Tests\Repositories\CartSessionRepository;

use App\Cart;
use App\Repositories\CartSessionRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class PersistentCartTest extends TestCase
{
    use DatabaseMigrations;

    public function test new cart()
    {
        // given
        //

        // when
        $cart = app(CartSessionRepository::class)->persistentCart();

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertTrue($cart->exists);
        $this->assertSame(1, $cart->id);
        $this->assertSame(1, Session::get('cart_id'));
    }

    public function test existing cart()
    {
        // given
        factory(Cart::class)->create(['paid_at' => null]);
        $this->session(['cart_id' => 1]);

        // when
        $cart = app(CartSessionRepository::class)->persistentCart();

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertTrue($cart->exists);
        $this->assertSame(1, $cart->id);
        $this->assertSame(1, Session::get('cart_id'));
    }
}
