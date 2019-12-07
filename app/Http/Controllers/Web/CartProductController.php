<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreCartProductRequest;
use App\Http\Resources\CartProductResource;
use App\Repositories\CartSessionRepository;

class CartProductController extends Controller
{
    /**
     * Attach the product to the cart bound to the current session.
     * Return the resulting cart product.
     *
     * @param StoreCartProductRequest $request
     * @param CartSessionRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCartProductRequest $request, CartSessionRepository $repository)
    {
        return new CartProductResource(
            $repository->addProduct($request->product(), $request->quantity)
        );
    }
}
