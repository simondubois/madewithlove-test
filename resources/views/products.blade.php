<h2 class="mb-4">

    <i class="fas fa-fw fa-boxes pr-1"></i>

    Products

    <span class="badge badge-pill badge-primary">
        {{ $products->count() }}
    </span>

</h2>

<div class="row justify-content-center">
    @each('product', $products, 'product')
</div>
