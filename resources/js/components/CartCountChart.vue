<script>

    export default {
        extends: require('vue-chartjs').Bar,
        mixins: [require('vue-chartjs').mixins.reactiveData],
        props: {
            end: {
                type: String,
                required: true,
            },
            primaryProduct: {
                type: Object,
                required: true,
            },
            secondaryProducts: {
                type: Array,
                required: true,
            },
            start: {
                type: String,
                required: true,
            },
        },
        computed: {
            cartProducts: vue => vue.$store.getters['cart/cartProducts'],
            options: () => ({
                legend: {
                    display: false,
                },
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true,
                        ticks: {
                            stepSize: 1,
                            suggestedMin: 0,
                        },
                    }],
                },
            }),
            params: vue => ({
                end: vue.end + ' 23:59:59',
                product_ids: vue.secondaryProducts.map(product => product.id),
                start: vue.start + ' 00:00:00',
            }),
            url: vue => 'api/cart_counts/product/' + vue.primaryProduct.id,
        },
        watch: {
            cartProducts() {
                this.refresh();
            },
            params() {
                this.refresh();
            },
            url() {
                this.refresh();
            },
        },
        created() {
        },
        methods: {
            buildChartData(data) {
                this.chartData = {
                    labels: this.secondaryProducts.map(product => product.name),
                    datasets: [
                        {
                            backgroundColor: '#56CC9D',
                            data: data.paid_existing_product,
                            label: 'Paid cart, non deleted product',
                        },
                        {
                            backgroundColor: '#FFCE67',
                            data: data.unpaid_existing_product,
                            label: 'Unpaid cart, non deleted product',
                        },
                        {
                            backgroundColor: '#fd7e14',
                            data: data.paid_deleted_product,
                            label: 'Paid cart, deleted product',
                        },
                        {
                            backgroundColor: '#FF7851',
                            data: data.unpaid_deleted_product,
                            label: 'Unpaid cart, deleted product',
                        },
                    ],
                };
            },
            refresh() {
                require('axios')
                    .get(this.url, { params: this.params })
                    .then(response => this.buildChartData(response.data));
            },
        },
    };

</script>
