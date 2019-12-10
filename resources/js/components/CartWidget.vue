<template>

    <div>

        <slot
            :quantity="quantity"
            :toggle="toggle"
        />

        <div
            v-if="opened"
            class="position-fixed border bg-white"
        >

            <div class="text-right">
                <button
                    class="btn btn-link btn-sm"
                    @click="toggle"
                >
                    <i class="fas fa-fw fa-times" />
                </button>
            </div>

            <cart-product-list />

        </div>

    </div>

</template>

<script>

    export default {
        components: {
            CartProductList: require('./CartProductList.vue').default,
        },
        data: () => ({
            opened: false,
        }),
        computed: {
            quantity: vue => vue.$store.getters['cart/quantity'],
        },
        methods: {
            toggle() {
                this.opened = !this.opened;
            }
        },
    };

</script>


<style lang="scss" scoped>

    @import '~bootstrap/scss/bootstrap';

    .position-fixed {
        top: 48px;
        right: 2%;
        width: 96%;
        z-index: $zindex-dropdown;
        text-align: center;
    }

</style>