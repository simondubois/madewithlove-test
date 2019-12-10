<template>

    <div
        v-if="paidAt"
        class="lead text-center"
    >
        <p>
            Payment accepted.<br>
            Thank you for your trust and your support.<br>
        </p>

        <p>
            Have a <i class="fas fa-fw fa-sun text-primary" /> day.
        </p>

        <p>
            <i class="fas fa-circle-notch fa-spin text-primary" />
        </p>

    </div>

    <div
        v-else-if="quantity === 0"
        class="lead text-center"
    >

        <p>
            Your cart is empty.
        </p>

        <a
            class="btn btn-link btn-sm"
            href="#products-section"
        >
            Back to products
        </a>

    </div>

    <div
        v-else
        class="row"
    >

        <div class="col-12 form-group">

            <label>
                Your selection
            </label>

            <cart-product-list wide />

        </div>

        <div class="col-md-4 form-group">
            <div class="form-group">

                <label for="checkout-name">
                    Full name
                </label>

                <input
                    id="checkout-name"
                    v-model="name"
                    :class="{ 'is-invalid': errors.name }"
                    class="form-control"
                    placeholder="Thomas Anderson"
                    type="name"
                >

                <div
                    v-for="(error, index) in errors.name"
                    :key="index"
                    class="invalid-feedback"
                >
                    {{ error }}
                </div>

            </div>
        </div>

        <div class="col-md-4 form-group">
            <div class="form-group">

                <label for="checkout-email">
                    Email address
                </label>

                <input
                    id="checkout-email"
                    v-model="email"
                    :class="{ 'is-invalid': errors.email }"
                    class="form-control"
                    placeholder="thomas.anderson@metacortex.com"
                    type="email"
                >

                <div
                    v-for="(error, index) in errors.email"
                    :key="index"
                    class="invalid-feedback"
                >
                    {{ error }}
                </div>

            </div>
        </div>

        <div class="col-md-4 form-group">

            <label class="d-block">
                Payment method
            </label>

            <div
                v-for="(availablePaymentMethod, index) in availablePaymentMethods"
                :key="availablePaymentMethod"
                class="custom-control custom-radio"
            >

                <input
                    :id="'checkout-payment_method-' + availablePaymentMethod"
                    v-model="paymentMethod"
                    :class="{ 'is-invalid': errors.payment_method }"
                    :value="availablePaymentMethod"
                    class="custom-control-input"
                    name="payment_method"
                    type="radio"
                >

                <label
                    class="custom-control-label"
                    :for="'checkout-payment_method-' + availablePaymentMethod"
                >
                    {{ paymentMethodTitles[availablePaymentMethod] }}
                </label>

                <template v-if="index + 1 === availablePaymentMethods.length">
                    <div
                        v-for="(errorMessage, errorIndex) in errors.payment_method"
                        :key="errorIndex"
                        class="invalid-feedback"
                    >
                        {{ errorMessage }}
                    </div>
                </template>

            </div>

        </div>

        <div class="col-md-12 form-group text-center">
            <button
                class="btn btn-primary"
                @click="pay"
            >
                <i class="fas fa-fw fa-credit-card pr-1" />
                Pay now
            </button>
        </div>

    </div>

</template>

<script>

    export default {
        components: {
            CartProductList: require('./CartProductList.vue').default,
        },
        data: () => ({
            email: '',
            errors: [],
            name: '',
            paymentMethod: '',
            paymentMethodTitles: {
                deus_ex: 'Credit Chit',
                elder_scrolls: 'Septims and drakes',
                fallout: 'Bottle caps',
            },
        }),
        computed: {
            availablePaymentMethods: vue => vue.$store.getters['cart/availablePaymentMethods'],
            cartProducts: vue => vue.$store.getters['cart/cartProducts'],
            originalEmail: vue => vue.$store.getters['cart/email'],
            originalName: vue => vue.$store.getters['cart/name'],
            originalPaymentMethod: vue => vue.$store.getters['cart/paymentMethod'],
            paidAt: vue => vue.$store.getters['cart/paidAt'],
            quantity: vue => vue.$store.getters['cart/quantity'],
            totalPrice: vue => new Intl.NumberFormat(document.documentElement.lang, { style: 'currency', currency: 'EUR' })
                .format(vue.$store.getters['cart/totalPrice']),
        },
        watch: {
            originalEmail() {
                this.email = this.originalEmail;
            },
            originalName() {
                this.name = this.originalName;
            },
            originalPaymentMethod() {
                this.paymentMethod = this.originalPaymentMethod;
            },
        },
        methods: {
            handleError(error) {
                if (error.response && error.response.status === 422) {
                    this.errors = error.response.data.errors;
                    return;
                }
                throw error;
            },
            pay() {
                this.errors = [];
                this.$store.dispatch('cart/pay', { email: this.email, name: this.name, paymentMethod: this.paymentMethod })
                    .catch(this.handleError)
                    .then(() => setTimeout(() => this.$store.dispatch('cart/refresh'), 3000))
            }
        }
    };

</script>
