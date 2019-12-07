<?php

namespace Tests\Repositories\CartSessionRepository;

use App\Cart;
use App\CartProduct;
use App\Product;
use App\Repositories\CartSessionRepository;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class AddProductTest extends TestCase
{
    use DatabaseMigrations;

    public function test new cart()
    {
        // given
        $product = factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);

        // when
        $cartProduct = app(CartSessionRepository::class)->addProduct($product, 10);

        // then
        $this->assertInstanceOf(CartProduct::class, $cartProduct);
        $this->assertFalse($cartProduct->isDirty());
        $this->assertSame(1, $cartProduct->id);
        $this->assertSame('AAA', $cartProduct->product_name);
        $this->assertSame(123.45, $cartProduct->product_price);
        $this->assertSame(1234.5, $cartProduct->total_price);
        $this->assertSame(10, $cartProduct->quantity);
        $this->assertInstanceOf(Cart::class, $cartProduct->cart);
        $this->assertSame(1, $cartProduct->cart->id);
        $this->assertSame(1234.50, $cartProduct->cart->total_price);
    }

    public function test existing cart with same product()
    {
        // given
        $product = factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $cartProduct = app(CartSessionRepository::class)->addProduct($product, 10);

        // then
        $this->assertInstanceOf(CartProduct::class, $cartProduct);
        $this->assertFalse($cartProduct->isDirty());
        $this->assertSame(1, $cartProduct->id);
        $this->assertSame('AAA', $cartProduct->product_name);
        $this->assertSame(123.45, $cartProduct->product_price);
        $this->assertSame(1357.95, $cartProduct->total_price);
        $this->assertSame(11, $cartProduct->quantity);
        $this->assertInstanceOf(Cart::class, $cartProduct->cart);
        $this->assertSame(1, $cartProduct->cart->id);
        $this->assertSame(1357.95, $cartProduct->cart->total_price);
    }

    public function test existing cart with trashed cart product()
    {
        // given
        $product = factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1])->delete();
        $this->session(['cart_id' => 1]);

        // when
        $cartProduct = app(CartSessionRepository::class)->addProduct($product, 10);

        // then
        $this->assertInstanceOf(CartProduct::class, $cartProduct);
        $this->assertFalse($cartProduct->trashed());
        $this->assertFalse($cartProduct->isDirty());
        $this->assertSame(1, $cartProduct->id);
        $this->assertSame('AAA', $cartProduct->product_name);
        $this->assertSame(123.45, $cartProduct->product_price);
        $this->assertSame(1234.5, $cartProduct->total_price);
        $this->assertSame(10, $cartProduct->quantity);
        $this->assertInstanceOf(Cart::class, $cartProduct->cart);
        $this->assertSame(1, $cartProduct->cart->id);
        $this->assertSame(1234.50, $cartProduct->cart->total_price);
    }

    public function test existing cart with different product()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        $productB = factory(Product::class)->create(['name' => 'BBB', 'price' => 678.90]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        $cartProduct = app(CartSessionRepository::class)->addProduct($productB, 10);

        // then
        $this->assertInstanceOf(CartProduct::class, $cartProduct);
        $this->assertFalse($cartProduct->isDirty());
        $this->assertSame(2, $cartProduct->id);
        $this->assertSame('BBB', $cartProduct->product_name);
        $this->assertSame(678.90, $cartProduct->product_price);
        $this->assertSame(6789.0, $cartProduct->total_price);
        $this->assertSame(10, $cartProduct->quantity);
        $this->assertInstanceOf(Cart::class, $cartProduct->cart);
        $this->assertSame(1, $cartProduct->cart->id);
        $this->assertSame(6912.45, $cartProduct->cart->total_price);
    }
}
