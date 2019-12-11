
<h2 class="mb-4">
    <i class="fas fa-fw fa-chart-bar pr-1"></i>
    Statistics
</h2>

<div class="d-flex flex-wrap justify-content-center mb-4">

    <div class="input-group input-group-sm w-auto mx-2">

        <div class="input-group-prepend">
            <span class="input-group-text">
                From
            </span>
        </div>

        <input
            v-model="statisticsStart"
            class="form-control"
            type="date"
        >

    </div>

    <div class="input-group input-group-sm w-auto mx-2">

        <div class="input-group-prepend">
            <span class="input-group-text">
                To
            </span>
        </div>

        <input
            v-model="statisticsEnd"
            class="form-control"
            type="date"
        >

    </div>

</div>

<div class="row justify-content-center">
    @foreach($products as $product)
        <div class="col-sm-6 col-lg-4 mb-5">

        <div class="mb-4 text-right">
            <div class="h5">
                Carts containing
            </div>
            <h3 class="text-truncate">
                {{ $product->name }}
            </h3>
            <div class="h5">
                and...
            </div>
        </div>

        <cart-count-chart
            :end="statisticsEnd"
            :primary-product='@json($product)'
            :secondary-products='@json($products->whereNotIn('id', $product->id)->values())'
            :start="statisticsStart"
        ></cart-count-chart>

        </div>
    @endforeach
</div>
