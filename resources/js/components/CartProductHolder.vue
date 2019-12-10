<script>

    export default {
        props: {
            cartProduct: {
                type: Object,
                required: true,
            },
        },
        computed: {
            id: vue => vue.cartProduct.id,
            name: vue => vue.cartProduct.product_name,
            picture: vue => 'https://source.unsplash.com/random/640*480/?' + vue.name,
            quantity: vue => vue.cartProduct.quantity,
            totalPrice: vue => new Intl.NumberFormat(document.documentElement.lang, { style: 'currency', currency: 'EUR' })
                .format(vue.cartProduct.total_price),
            unitPrice: vue => new Intl.NumberFormat(document.documentElement.lang, { style: 'currency', currency: 'EUR' })
                .format(vue.cartProduct.product_price),
            url: vue => '#product-box-' + vue.cartProduct.product_id,
        },
        methods: {
            destroy() {
                this.$store.dispatch('cart/destroyCartProduct', this.id);
            },
            update(quantity) {
                this.$store.dispatch('cart/updateCartProduct', { id: this.id, quantity });
            },
        },
        render() {
            return this.$scopedSlots.default({
                destroy: this.destroy,
                name: this.name,
                picture: this.picture,
                quantity: this.quantity,
                totalPrice: this.totalPrice,
                unitPrice: this.unitPrice,
                update: this.update,
                url: this.url,
            });
        },
    };

</script>
