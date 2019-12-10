
const Vue = require('vue');

const Vuex = require('vuex').default;
Vue.use(Vuex);

new Vue({
    el: '#app',
    components: {
        CartWidget: require('./components/CartWidget.vue').default,
        CheckoutForm: require('./components/CheckoutForm.vue').default,
    },
    store: new Vuex.Store({
        modules: {
            cart: {
                namespaced: true,
                ...require('./stores/cart.js'),
            },
        },
    }),
    created() {
        this.$store.dispatch('cart/refresh');
    },
    methods: {
        addToCart(productId, quantity) {
            this.$store.dispatch('cart/createCartProduct', { productId, quantity });
        },
    },
});
