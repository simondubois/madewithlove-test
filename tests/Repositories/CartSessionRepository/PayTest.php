<?php

namespace Tests\Repositories\CartSessionRepository;

use App\Cart;
use App\CartProduct;
use App\Exceptions\EmptyCartException;
use App\Product;
use App\Repositories\CartSessionRepository;
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

    public function test new cart()
    {
        $this->expectException(EmptyCartException::class);

        // given
        //

        // when
        app(CartSessionRepository::class)->pay();

        // then
        $this->assertNull(Cart::first());
    }

    public function test empty cart()
    {
        $this->expectException(EmptyCartException::class);

        // given
        factory(Cart::class)->create(['paid_at' => null]);
        $this->session(['cart_id' => 1]);

        // when
        app(CartSessionRepository::class)->pay();

        // then
        $this->assertNull(Cart::first()->paid_at);
    }

    public function test filled cart()
    {
        // given
        Carbon::setTestNow(Carbon::now());
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $cart = app(CartSessionRepository::class)->pay();

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertFalse($cart->isDirty());
        $this->assertSame(1, $cart->id);
        $this->assertSame(Carbon::getTestNow()->timestamp, $cart->paid_at->timestamp);
        Carbon::setTestNow();
    }
}
