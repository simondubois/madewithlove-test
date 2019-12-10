<template>

    <cart-product-holder :cart-product="cartProduct">
        <div
            slot-scope="{ destroy, name, picture, quantity, totalPrice, unitPrice, update }"
            class="d-flex flex-wrap justify-content-center align-items-center"
        >

            <img
                :alt="name"
                :src="picture"
                class="img-fluid mx-3"
            >

            <div class="flex-fill text-center text-sm-left mx-3">

                <h5 class="m-0">
                    {{ name }}
                </h5>

                <small class="text-muted">
                    {{ unitPrice }}
                </small>

            </div>

            <div class="mx-3">
                <div class="input-group mb-0">

                    <div class="input-group-prepend">
                        <button
                            :disabled="wide && quantity <= 1"
                            class="btn btn-link"
                            @click="update(quantity - 1)"
                        >
                            <i class="fas fa-fw fa-minus" />
                        </button>
                    </div>

                    <input
                        class="fomr-control text-center border-0"
                        size="3"
                        type="number"
                        :value="quantity"
                        @change="update($event.target.value)"
                    >

                    <div class="input-group-append">
                        <button
                            :disabled="quantity >= 100"
                            class="btn btn-link"
                            @click="update(quantity + 1)"
                        >
                            <i class="fas fa-fw fa-plus" />
                        </button>
                    </div>

                    <div
                        v-if="wide"
                        class="input-group-append"
                    >
                        <button
                            class="btn btn-outline-danger border-0"
                            @click="destroy"
                        >
                            <i class="fas fa-fw fa-trash" />
                        </button>
                    </div>

                </div>
            </div>

            <div
                v-if="wide"
                class="mx-3"
            >
                {{ totalPrice }}
            </div>

        </div>
    </cart-product-holder>

</template>

<script>

    export default {
        components: {
            CartProductHolder: require('./CartProductHolder.vue').default,
        },
        props: {
            cartProduct: {
                type: Object,
                required: true,
            },
            wide: {
                type: Boolean,
                default: false,
            },
        },
    };

</script>

<style lang="scss" scoped>

    .img-fluid {
        max-height: 4rem;
    }

    input[type=number] {

        width: 2rem;

        &::-webkit-inner-spin-button,
        &::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

    }

</style>
