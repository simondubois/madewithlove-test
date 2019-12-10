<div class="col-sm-6 col-lg-4 mb-4">
    <div class="card border-0">

        <img src="{{ $product->image_url }}" class="card-img-top" alt="{{ $product->name }}">

        <div class="card-body d-flex align-items-baseline justify-content-between">

            <h5 class="card-title">
                {{ $product->name }}
                <small class="text-muted">
                    {{ money_format('%i', $product->price) }}
                </small>
            </h5>

            <button class="btn btn-primary btn-sm" @click="addToCart({{ $product->id }}, 1)">
                <i class="fas fa-fw fa-cart-plus pr-1"></i>
            </button>

        </div>

    </div>
</div>
