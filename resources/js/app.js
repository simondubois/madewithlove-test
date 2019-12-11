
const Vue = require('vue');

const Vuex = require('vuex').default;
Vue.use(Vuex);

new Vue({
    el: '#app',
    components: {
        CartCountChart: require('./components/CartCountChart.vue').default,
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
    data: () => ({
        statisticsEnd: new Date().toISOString().slice(0, 10),
        statisticsStart: new Date().toISOString().slice(0, 10),
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
