<?php

namespace App\Repositories;

use App\Cart;
use App\CartProduct;
use App\Product;
use Illuminate\Support\Facades\Session;

class CartSessionRepository extends CartRepository
{
    /**
     * Current HTTP session
     *
     * @var Session
     */
    protected $session;

    /**
     * Make a new repository instance, where the cart is bound to the session.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Make the cart persistent first.
     * @inheritDoc
     *
     * @param Product $product
     * @param integer $quantity
     * @return CartProduct
     */
    public function addProduct(Product $product, int $quantity): CartProduct
    {
        $this->persistentCart();

        return parent::addProduct($product, $quantity);
    }

    /**
     * Return the cart bound to the current session.
     * If no cart exists yet, a new unsaved cart will be returned.
     * Hence he database is not spoiled with empty carts from non-buyers.
     *
     * @return Cart
     */
    public function cart(): Cart
    {
        $cartId = $this->session::get('cart_id');

        if (is_null($cartId)) {
            return new Cart();
        }

        return Cart::query()
            ->whereNull('paid_at')
            ->findOrNew($cartId);
    }

    /**
     * Return the cart bound to the current session.
     * If no cart exists yet, a new one is created and bound to the session.
     *
     * @return Cart
     */
    public function persistentCart(): Cart
    {
        $cart = $this->cart();

        if ($cart->exists) {
            return $cart;
        }

        $cart->save();

        $this->session::put('cart_id', $cart->id);

        return $cart;
    }
}
