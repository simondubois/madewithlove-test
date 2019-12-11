<?php

namespace App\Repositories;

use App\Cart;
use App\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class CartCountRepository
{
    /**
     * Undocumented variable
     *
     * @var Builder
     */
    protected $query;

    /**
     * Make a new repository instance with a fresh cart query.
     */
    public function __construct()
    {
        $this->query = Cart::query();
    }

    /**
     * Return the number of carts matching the query.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return int
     */
    public function countCarts(): int
    {
        return $this->query->count();
    }

    /**
     * Reduce result set to cart created in the provided period.
     *
     * @param Carbon $start
     * @param Carbon $end
     * @return CartCountRepository
     */
    public function inPeriod(Carbon $start, Carbon $end): CartCountRepository
    {
        $this->query->whereBetween('created_at', [$start, $end]);

        return $this;
    }

    /**
     * Reduce result set to paid carts.
     *
     * @return CartCountRepository
     */
    public function paid(): CartCountRepository
    {
        $this->query->whereNotNull('paid_at');

        return $this;
    }

    /**
     * Reduce result set to unpaid carts.
     *
     * @return CartCountRepository
     */
    public function unpaid(): CartCountRepository
    {
        $this->query->whereNull('paid_at');

        return $this;
    }

    /**
     * Reduce result set to carts of which the provided product has been removed.
     *
     * @param Product $product
     * @return CartCountRepository
     */
    public function withDeletedProduct(Product $product): CartCountRepository
    {
        $this->query->whereHas('cartProducts', function (Builder $query) use ($product): void {
            $query->onlyTrashed()->where('product_id', $product->id);
        });

        return $this;
    }

    /**
     * Reduce result set to carts including the provided product.
     *
     * @param Product $product
     * @return CartCountRepository
     */
    public function withProduct(Product $product): CartCountRepository
    {
        $this->query->whereHas('cartProducts', function (Builder $query) use ($product): void {
            $query->where('product_id', $product->id);
        });

        return $this;
    }
}
