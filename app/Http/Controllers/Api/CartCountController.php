<?php

namespace App\Http\Controllers\Api;

use App\Product;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CartCountProductRequest;
use App\Repositories\CartCountRepository;

class CartCountController extends Controller
{
    /**
     * Return the cart bound to the current session.
     *
     * @param CartCountProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function product(CartCountProductRequest $request, Product $product)
    {
        $pairs = $request->products()->crossJoin([$product])->map('array_reverse');

        return [
            'paid_existing_product' => $pairs->mapSpread(function (Product $one, Product $two) use ($request): int {
                return app(CartCountRepository::class)
                    ->paid()
                    ->withProduct($one)
                    ->withProduct($two)
                    ->inPeriod($request->start(), $request->end())
                    ->countCarts();
            }),
            'paid_deleted_product' => $pairs->mapSpread(function (Product $one, Product $two) use ($request): int {
                return app(CartCountRepository::class)
                    ->paid()
                    ->withProduct($one)
                    ->withDeletedProduct($two)
                    ->inPeriod($request->start(), $request->end())
                    ->countCarts();
            }),
            'unpaid_existing_product' => $pairs->mapSpread(function (Product $one, Product $two) use ($request): int {
                return app(CartCountRepository::class)
                    ->unpaid()
                    ->withProduct($one)
                    ->withProduct($two)
                    ->inPeriod($request->start(), $request->end())
                    ->countCarts();
            }),
            'unpaid_deleted_product' => $pairs->mapSpread(function (Product $one, Product $two) use ($request): int {
                return app(CartCountRepository::class)
                    ->unpaid()
                    ->withProduct($one)
                    ->withDeletedProduct($two)
                    ->inPeriod($request->start(), $request->end())
                    ->countCarts();
            }),
        ];
    }
}
