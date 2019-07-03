<template>
    <div id="EtagereBoutique">
        <div
            v-for="(product, index) in productsSorted"
            :key="index"
            class="col-md-3 col-xs-6 product-tile-container"
        >
            <div class="col-xs-12 product-tile">
                <ProduitTile :produit="product" />
            </div>
        </div>
    </div>
</template>

<script>
import ProduitTile from './ProductTile';

export default {
    name: 'EtagereBoutique',
    components: {
        ProduitTile
    },
    computed: {
        etageres: {
            get () {
                return this.$store.getters.getEtagereBoutiqueClient;
            }
        },
        products () {
            let products = [];

            this.etageres.forEach(etagere => {
                etagere.produit.forEach(produit => {
                    if (produit.id_produit && !this.isOutOfStock(produit)) {
                        products.push(produit);
                    }
                });
            });

            return products;
        },
        productsOutOfStock () {
            let productsOutOfStock = [];

            this.etageres.forEach(etagere => {
                etagere.produit.forEach(produit => {
                    if (produit.id_produit && this.isOutOfStock(produit)) {
                        productsOutOfStock.push(produit);
                    }
                });
            });

            return productsOutOfStock;
        },
        productsSorted () {
            const productsSorted = [...this.products, ...this.productsOutOfStock];

            return productsSorted;
        }
    },
    methods: {
        isOutOfStock (product) {
            let isOutOfStock = true;
            for (let i = 0; i < product.id_produit.stocks.length; i++) {
                if (product.id_produit.stocks[i].activer && product.id_produit.stocks[i].afficher) {
                    isOutOfStock = false;
                }
            }

            return isOutOfStock;
        }
    }
};
</script>

<style lang="scss" scoped>
#EtagereBoutique {
    .product-tile-container {
        display: flex;
        justify-content: center;
        position: relative;
        margin-bottom: 25px;
        min-height: 285px;

        .product-tile {
            margin: 0;
            padding: 0;
            height: auto;
            width: 200px;
        }
    }
}
</style>
