<?php

namespace App\Http\Controllers\Web;

use App\CartProduct;
use App\Exceptions\UnrelatedCartProductException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreCartProductRequest;
use App\Http\Requests\Web\UpdateCartProductRequest;
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

    /**
     * Update quantity for one cart product bound to the current session.
     * Return the updated cart product.
     *
     * @param UpdateCartProductRequest $request
     * @param CartProduct $cartProduct
     * @param CartSessionRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function update(
        UpdateCartProductRequest $request,
        CartProduct $cartProduct,
        CartSessionRepository $repository
    ) {
        try {
            $cartProduct = $repository->updateCartProduct($cartProduct, $request->quantity);
        } catch (UnrelatedCartProductException $exception) {
            abort(403, 'UNRELATED_CART_PRODUCT');
        }

        if ($cartProduct->trashed()) {
            return response()->noContent();
        }

        return new CartProductResource(
            $cartProduct
        );
    }

    /**
     * Remove cart product from the cart bound to the current session.
     *
     * @param CartProduct $cartProduct
     * @param CartSessionRepository $repository
     * @return \Illuminate\Http\Response
     */
    public function destroy(CartProduct $cartProduct, CartSessionRepository $repository)
    {
        try {
            $repository->deleteCartProduct($cartProduct);
        } catch (UnrelatedCartProductException $exception) {
            abort(403, 'UNRELATED_CART_PRODUCT');
        }

        return response()->noContent();
    }
}
