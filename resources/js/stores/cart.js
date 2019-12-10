
const state = {
    cartProducts: [],
    email: '',
    name: '',
    paidAt: null,
    paymentMethod: '',
    availablePaymentMethods: '',
    totalPrice: 0,
};

const getters = {
    availablePaymentMethods: state => state.availablePaymentMethods,
    cartProducts: state => state.cartProducts,
    email: state => state.email,
    name: state => state.name,
    paidAt: state => state.paidAt,
    paymentMethod: state => state.paymentMethod,
    quantity: state => state.cartProducts.reduce((total, cartProduct) => total + cartProduct.quantity, 0),
    totalPrice: state => state.totalPrice,
};

const mutations = {
    availablePaymentMethods: (state, availablePaymentMethods) => state.availablePaymentMethods = availablePaymentMethods,
    cartProducts: (state, cartProducts) => state.cartProducts = cartProducts,
    email: (state, email) => state.email = email,
    name: (state, name) => state.name = name,
    paidAt: (state, paidAt) => state.paidAt = paidAt,
    paymentMethod: (state, paymentMethod) => state.paymentMethod = paymentMethod,
    totalPrice: (state, totalPrice) => state.totalPrice = totalPrice,
};

const actions = {
    createCartProduct: ({ dispatch }, { productId: product_id, quantity }) =>
        require('axios')
            .post('cart_products', { product_id, quantity })
            .then(() => dispatch('refresh')),
    destroyCartProduct: ({ dispatch }, id) =>
        require('axios')
            .delete('cart_products/' + id)
            .then(() => dispatch('refresh')),
    pay: ({ commit }, { email, name, paymentMethod: payment_method }) =>
        require('axios')
            .put('cart/pay', { email, name, payment_method })
            .then(response => {
                commit('availablePaymentMethods', response.data.data.available_payment_methods);
                commit('cartProducts', response.data.data.cartProducts);
                commit('email', response.data.data.email);
                commit('name', response.data.data.name);
                commit('paidAt', response.data.data.paid_at);
                commit('paymentMethod', response.data.data.payment_method);
                commit('totalPrice', response.data.data.total_price);
            }),
    refresh: ({ commit }) =>
        require('axios')
            .get('cart/show')
            .then(response => {
                commit('availablePaymentMethods', response.data.data.available_payment_methods);
                commit('cartProducts', response.data.data.cartProducts);
                commit('email', response.data.data.email);
                commit('name', response.data.data.name);
                commit('paidAt', response.data.data.paid_at);
                commit('paymentMethod', response.data.data.payment_method);
                commit('totalPrice', response.data.data.total_price);
            }),
    updateCartProduct: ({ dispatch }, { id, quantity }) =>
        require('axios')
            .put('cart_products/' + id, { quantity })
            .then(() => dispatch('refresh')),
};

export {
    state,
    getters,
    mutations,
    actions,
};
