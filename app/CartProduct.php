<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CartProduct extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'cart_id' => 'integer',
        'product_id' => 'integer',
        'product_price' => 'float',
        'total_price' => 'float',
        'quantity' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the cart that owns the cart product.
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Get the product that owns the cart product.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
