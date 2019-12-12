<?php

namespace App\Repositories;

use App\Cart;
use App\CartProduct;
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
}
