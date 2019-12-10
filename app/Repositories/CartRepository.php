<?php

namespace App\Repositories;

use App\Cart;
use App\CartProduct;
use App\Exceptions\UnrelatedCartProductException;
use App\Product;

abstract class CartRepository
{
    /**
     * Return the cart handled by the repository.
     *
     * @return Cart
     */
    abstract public function cart(): Cart;

    /**
     * Add the product to cart for the provided quantity.
     * If the product is already in cart, increase the quantity with the provided value.
     *
     * @param Product $product
     * @param integer $quantity
     * @return CartProduct
     */
    public function addProduct(Product $product, int $quantity): CartProduct
    {
        // find existing cart product related to the provided product, make a new cart product otherwise
        $cartProduct = $this->cart()
            ->cartProducts()
            ->withTrashed()
            ->where('product_id', $product->id)
            ->firstOrNew([]);

        // restore the cart product if it has been previously deleted, and reset the quantity
        if ($cartProduct->trashed()) {
            $cartProduct->restore();
            $cartProduct->quantity = 0;
        }

        // update cart product fields
        $cartProduct->product_id = $product->id;
        $cartProduct->product_name = $product->name;
        $cartProduct->product_price = $product->price;
        $cartProduct->quantity += $quantity;
        $cartProduct->total_price = $cartProduct->product_price * $cartProduct->quantity;

        // save to database
        $cartProduct->save();
        $this->recalculateCart();

        return $cartProduct;
    }

    /**
     * All valid payment_method values for the cart.
     *
     * @return array
     */
    public function availablePaymentMethods(): array
    {
        return [
            'deus_ex',
            'elder_scrolls',
            'fallout',
        ];
    }

    /**
     * Update cart's total_price.
     *
     * @return void
     */
    protected function recalculateCart(): void
    {
        $cart = $this->cart();
        $cart->total_price = $cart->cartProducts()->sum('total_price');
        $cart->save();
    }

    /**
     * Update the cart product with the provided quantity.
     * If the cart product does not belongs to the current cart, UnrelatedCartProductException is thrown.
     * If the cart product was deleted, it is restored.
     *
     * @throws UnrelatedCartProductException
     * @param CartProduct $cartProduct
     * @param integer $quantity
     * @return CartProduct
     */
    public function updateCartProduct(CartProduct $cartProduct, int $quantity): CartProduct
    {
        if ($cartProduct->cart->isNot($this->cart())) {
            throw new UnrelatedCartProductException();
        }

        if ($cartProduct->trashed()) {
            $cartProduct->restore();
        }

        $cartProduct->quantity = $quantity;
        $cartProduct->total_price = $cartProduct->product_price * $cartProduct->quantity;

        $cartProduct->save();
        $this->recalculateCart();

        return $cartProduct->load('cart');
    }
}
