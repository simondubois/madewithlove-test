<?php

namespace Tests\Repositories\CartSessionRepository;

use App\Cart;
use App\CartProduct;
use App\Exceptions\UnrelatedCartProductException;
use App\Product;
use App\Repositories\CartSessionRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class UpdateCartProductTest extends TestCase
{
    use DatabaseMigrations;

    public function test unrelated cart product()
    {
        $this->expectException(UnrelatedCartProductException::class);

        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(Cart::class)->create(['paid_at' => null]);
        $createdCartProduct = factory(CartProduct::class)->create(['cart_id' => 2, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $updatedCartProduct = app(CartSessionRepository::class)->updateCartProduct($createdCartProduct, 10);

        // then
        $this->assertInstanceOf(CartProduct::class, $updatedCartProduct);
        $this->assertFalse($updatedCartProduct->isDirty());
        $this->assertFalse($updatedCartProduct->trashed());
        $this->assertSame(1, $updatedCartProduct->quantity);
        $this->assertSame(1234.5, $updatedCartProduct->total_price);
        $this->assertSame(1234.5, $updatedCartProduct->cart->total_price);
    }

    public function test deleted cart product()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        $createdCartProduct = factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $createdCartProduct->delete();
        $this->session(['cart_id' => 1]);

        // when
        $updatedCartProduct = app(CartSessionRepository::class)->updateCartProduct($createdCartProduct, 10);

        // then
        $this->assertInstanceOf(CartProduct::class, $updatedCartProduct);
        $this->assertFalse($updatedCartProduct->isDirty());
        $this->assertFalse($updatedCartProduct->trashed());
        $this->assertSame(10, $updatedCartProduct->quantity);
        $this->assertSame(1234.5, $updatedCartProduct->total_price);
        $this->assertSame(1234.5, $updatedCartProduct->cart->total_price);
    }

    public function test existing cart product()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        $createdCartProduct = factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $updatedCartProduct = app(CartSessionRepository::class)->updateCartProduct($createdCartProduct, 10);

        // then
        $this->assertInstanceOf(CartProduct::class, $updatedCartProduct);
        $this->assertFalse($updatedCartProduct->isDirty());
        $this->assertFalse($updatedCartProduct->trashed());
        $this->assertSame(10, $updatedCartProduct->quantity);
        $this->assertSame(1234.5, $updatedCartProduct->total_price);
        $this->assertSame(1234.5, $updatedCartProduct->cart->total_price);
    }

    public function test existing cart with zero quantity()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        $createdCartProduct = factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $updatedCartProduct = app(CartSessionRepository::class)->updateCartProduct($createdCartProduct, 0);

        // then
        $this->assertInstanceOf(CartProduct::class, $updatedCartProduct);
        $this->assertFalse($updatedCartProduct->isDirty());
        $this->assertTrue($updatedCartProduct->trashed());
        $this->assertSame(1, $updatedCartProduct->quantity);
        $this->assertSame(123.45, $updatedCartProduct->total_price);
        $this->assertSame(0.0, $updatedCartProduct->cart->total_price);
    }
}
