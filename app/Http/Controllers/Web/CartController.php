<?php

namespace App\Http\Controllers\Web;

use App\Exceptions\EmptyCartException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\PayCartRequest;
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

    /**
     * Pay the cart bound to the current session.
     * Return the paid cart.
     *
     * @param PayCartRequest $request
     * @param CartSessionRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function pay(PayCartRequest $request, CartSessionRepository $repository)
    {
        $repository->updatePersonalInformation($request->email, $request->name);
        $repository->updatePaymentMethod($request->payment_method);

        try {
            $cart = $repository->pay();
        } catch (EmptyCartException $exception) {
            abort(422, 'EMPTY_CART');
        }

        return new CartResource(
            $cart->load('cartProducts'),
            $repository
        );
    }
}
