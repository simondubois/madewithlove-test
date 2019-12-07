<?php

namespace App\Http\Requests\Web;

use App\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCartProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the product matching the request.
     * Can return null if the request has not been validated yet and `product_id` is invalid.
     *
     * @return Product|null
     */
    public function product(): ?Product
    {
        return Product::find($this->product_id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_id' => [
                'required',
                Rule::exists('products', 'id'),
            ],
            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],
        ];
    }
}
