<?php

namespace App\Http\Resources;

use App\Repositories\CartRepository;
use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Cart repository
     *
     * @var CartRepository|null
     */
    protected $cartRepository;

    /**
     * Assign the provided cart repository.
     * @inheritDoc
     *
     * @param mixed $resource
     * @param CartRepository $cartRepository
     * @return void
     */
    public function __construct($resource, CartRepository $cartRepository = null)
    {
        $this->cartRepository = $cartRepository;

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'payment_method' => $this->payment_method,
            'total_price' => $this->total_price,
            'paid_at' => $this->paid_at ? $this->paid_at->toDateTimeString() : null,
            'available_payment_methods' => $this->when($this->cartRepository, function (): array {
                return $this->cartRepository->availablePaymentMethods();
            }),
            'cartProducts' => CartProductResource::collection($this->whenLoaded('cartProducts')),
        ];
    }
}
