<?php

namespace App\Http\Requests\Api;

use App\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;

class CartCountProductRequest extends FormRequest
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
     * Return the end parameter as Carbon instance.
     *
     * @return Carbon
     */
    public function end(): Carbon
    {
        return Carbon::parse($this->end);
    }

    /**
     * Return the product_ids parameter as a collection of Product instance.
     *
     * @return Collection
     */
    public function products(): Collection
    {
        return Product::find($this->product_ids) ?: collect();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'end' => [
                'required',
                'date',
            ],
            'product_ids.*' => [
                Rule::exists('products', 'id'),
            ],
            'start' => [
                'required',
                'date',
            ],
        ];
    }

    /**
     * Return the start parameter as Carbon instance.
     *
     * @return Carbon
     */
    public function start(): Carbon
    {
        return Carbon::parse($this->start);
    }
}
