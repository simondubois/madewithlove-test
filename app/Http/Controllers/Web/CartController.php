<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Repositories\CartSessionRepository;

class CartController extends Controller
{
    /**
     * Return the cart bound to the current session.
     *
     * @param CartSessionRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function show(CartSessionRepository $repository)
    {
        return new CartResource(
            $repository->cart()->load('cartProducts'),
            $repository
        );
    }
}
