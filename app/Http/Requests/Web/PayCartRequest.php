<?php

namespace App\Http\Requests\Web;

use App\Repositories\CartSessionRepository;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PayCartRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'payment_method' => [
                'required',
                Rule::in(app(CartSessionRepository::class)->availablePaymentMethods()),
            ],
        ];
    }
}
