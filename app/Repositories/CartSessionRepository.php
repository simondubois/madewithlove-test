<?php

namespace App\Repositories;

use App\Cart;
use Illuminate\Support\Facades\Session;

class CartSessionRepository
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
}
