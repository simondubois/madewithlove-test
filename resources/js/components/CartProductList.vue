<template>

    <div class="list-group list-group-flush">

        <div
            v-for="cartProduct in cartProducts"
            :key="cartProduct.id"
            class="list-group-item"
        >
            <cart-product-list-item
                :cart-product="cartProduct"
                :wide="wide"
            />
        </div>

        <div class="list-group-item">
            <div class="d-flex">

                <div class="flex-fill px-3 text-right">
                    Total
                </div>

                <div class="px-3 font-weight-bold">
                    {{ totalPrice }}
                </div>

            </div>
        </div>

    </div>

</template>

<script>

    export default {
        components: {
            CartProductListItem: require('./CartProductListItem.vue').default,
        },
        props: {
            wide: {
                type: Boolean,
                default: false,
            },
        },
        computed: {
            cartProducts: vue => vue.$store.getters['cart/cartProducts'],
            totalPrice: vue => new Intl.NumberFormat(document.documentElement.lang, { style: 'currency', currency: 'EUR' })
                .format(vue.$store.getters['cart/totalPrice']),
        }
    };

</script>
