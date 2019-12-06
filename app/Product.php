<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'price' => 'float',
    ];

    /**
     * Return the URL for the image representing the product.
     * Since no image has been provided with the instructions, Unspash will serve an image matching the product name.
     *
     * @return string
     */
    public function getImageUrlAttribute(): string
    {
        return 'https://source.unsplash.com/random/640*480/?' . $this->name;
    }
}
