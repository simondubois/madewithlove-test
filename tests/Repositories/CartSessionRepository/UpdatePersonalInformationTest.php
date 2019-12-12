<?php

namespace Tests\Repositories\CartSessionRepository;

use App\Cart;
use App\Repositories\CartSessionRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class UpdatePersonalInformationTest extends TestCase
{
    use DatabaseMigrations;

    public function test new cart()
    {
        // given
        //

        // when
        $cart = app(CartSessionRepository::class)
            ->updatePersonalInformation('thomas.anderson@metacortex.com', 'Thomas Anderson');

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertFalse($cart->isDirty());
        $this->assertSame(1, $cart->id);
        $this->assertSame('thomas.anderson@metacortex.com', $cart->email);
        $this->assertSame('Thomas Anderson', $cart->name);
    }

    public function test existing cart()
    {
        // given
        factory(Cart::class)->create(['paid_at' => null]);
        $this->session(['cart_id' => 1]);

        // when
        $cart = app(CartSessionRepository::class)
            ->updatePersonalInformation('thomas.anderson@metacortex.com', 'Thomas Anderson');

        // then
        $this->assertInstanceOf(Cart::class, $cart);
        $this->assertFalse($cart->isDirty());
        $this->assertSame(1, $cart->id);
        $this->assertSame('thomas.anderson@metacortex.com', $cart->email);
        $this->assertSame('Thomas Anderson', $cart->name);
    }
}
