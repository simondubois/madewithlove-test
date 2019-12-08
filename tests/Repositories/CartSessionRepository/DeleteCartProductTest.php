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
class DeleteCartProductTest extends TestCase
{
    use DatabaseMigrations;

    public function test unrelated cart product()
    {
        $this->expectException(UnrelatedCartProductException::class);

        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Product::class)->create(['name' => 'BBB', 'price' => 678.90]);
        factory(Cart::class)->create(['paid_at' => null]);
        factory(Cart::class)->create(['paid_at' => null]);
        $cartProduct = factory(CartProduct::class)->create(['cart_id' => 2, 'product_id' => 1, 'quantity' => 1]);
        $this->session(['cart_id' => 1]);

        // when
        app(CartSessionRepository::class)->deleteCartProduct($cartProduct);

        // then
        $this->assertDatabaseHas('cart_products', [
            'id' => 1,
            'cart_id' => 2,
            'product_id' => 1,
            'quantity' => 1,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseHas('carts', [
            'id' => 2,
            'total_price' => 123.45,
        ]);
    }

    public function test deleted cart product()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Product::class)->create(['name' => 'BBB', 'price' => 678.90]);
        factory(Cart::class)->create(['paid_at' => null]);
        $cartProduct = factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        $cartProduct->delete();
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 2, 'quantity' => 10]);
        $this->session(['cart_id' => 1]);

        // when
        app(CartSessionRepository::class)->deleteCartProduct($cartProduct);

        // then
        $this->assertSoftDeleted('cart_products', [
            'id' => 1,
            'cart_id' => 1,
            'product_id' => 1,
            'quantity' => 1,
        ]);
        $this->assertDatabaseHas('cart_products', [
            'id' => 2,
            'cart_id' => 1,
            'product_id' => 2,
            'quantity' => 10,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseHas('carts', [
            'id' => 1,
            'total_price' => 6789,
        ]);
    }

    public function test existing cart product()
    {
        // given
        factory(Product::class)->create(['name' => 'AAA', 'price' => 123.45]);
        factory(Product::class)->create(['name' => 'BBB', 'price' => 678.90]);
        factory(Cart::class)->create(['paid_at' => null]);
        $cartProduct = factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 1, 'quantity' => 1]);
        factory(CartProduct::class)->create(['cart_id' => 1, 'product_id' => 2, 'quantity' => 10]);
        $this->session(['cart_id' => 1]);

        // when
        app(CartSessionRepository::class)->deleteCartProduct($cartProduct);

        // then
        $this->assertSoftDeleted('cart_products', [
            'id' => 1,
            'cart_id' => 1,
            'product_id' => 1,
            'quantity' => 1,
        ]);
        $this->assertDatabaseHas('cart_products', [
            'id' => 2,
            'cart_id' => 1,
            'product_id' => 2,
            'quantity' => 10,
            'deleted_at' => null,
        ]);
        $this->assertDatabaseHas('carts', [
            'id' => 1,
            'total_price' => 6789,
        ]);
    }
}
