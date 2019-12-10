<nav class="navbar navbar-expand navbar-expand-lg navbar-dark bg-primary">
    <div class="container">

        <a class="d-none d-sm-block navbar-brand">
            madewithlove
        </a>

        <ul class="navbar-nav mr-auto">

            <li class="nav-item">
                <a class="nav-link" href="#products-section">

                    <i class="fas fa-fw fa-boxes"></i>

                    <span class="d-none d-sm-inline pl-1">
                        Products
                    </span>

                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#checkout-section">

                    <i class="fas fa-fw fa-cash-register"></i>

                    <span class="d-none d-sm-inline pl-1">
                        Checkout
                    </span>

                </a>
            </li>

        </ul>

        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <cart-widget>
                    <a
                        class="nav-link dropdown-toggle"
                        href="#"
                        slot-scope="{ quantity, toggle }"
                        @click.prevent="toggle"
                    >
                        <i class="fas fa-fw fa-shopping-cart"></i>
                        <span class="badge badge-pill badge-light">@{{ quantity }}</span>
                    </a>
                </cart-widget>
            </li>
        </ul>

    </div>
</nav>
