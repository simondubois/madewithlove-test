<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'total_price' => 0.0,
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'total_price' => 'float',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the cart products for the cart.
     */
    public function cartProducts()
    {
        return $this->hasMany(CartProduct::class);
    }
}
