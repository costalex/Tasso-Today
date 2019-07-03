<template>
    <div>
        <div v-if="produit.id_produit">
            <div
                v-for="(stock, index) in produit.id_produit.stocks"
                v-if="stock.activer && stock.afficher"
                id="ProductTile"
                :key="index"
            >
                <div class="col-xs-12 product-tile-image">
                    <img
                        :src="produit.id_produit.path_file_photo_principale[0].image_miniature[2]"
                        class="product-miniature"
                    >

                    <div class="product-btn">
                        <img
                            id="show-modal"
                            :data-target="'#myModal'+ produit.id_produit.id"
                            class="product-details"
                            data-toggle="modal"
                            src="/storage/bobby_images/marketplace-client/btn-view.svg"
                            @click="modalOpen(produit, stock)"
                        >

                        <img
                            v-if="produit.id_produit.stocks.length === 1"
                            class="quick-add-to-cart"
                            src="/storage/bobby_images/marketplace-client/btn-cart.svg"
                            @click="addProduct(produit, stock)"
                        >

                        <img
                            v-else
                            id="show-modal"
                            :data-target="'#myModal'+ produit.id_produit.id"
                            class="product-details"
                            data-toggle="modal"
                            src="/storage/bobby_images/marketplace-client/btn-cart.svg"
                            @click="modalOpen(produit, stock)"
                        >
                    </div>
                </div>

                <div class="col-xs-12 product-tile-name">
                    <h3>{{ produit.id_produit.nom }}</h3>

                    <div
                        v-for="(stock, index) in produit.id_produit.stocks"
                        :key="index"
                    >
                        <h2 v-if="stock.activer && stock.afficher">
                            {{ (stock.prix * 1).toFixed(2).replace('.', ',') }}€
                        </h2>
                    </div>
                </div>
            </div>

            <div
                v-if="epuiser(produit) || ShopStatus === 'FERME_J'"
                id="ProductTile"
            >
                <div class="col-xs-12 product-tile-image product-out-of-stock">
                    <img
                        :src="produit.id_produit.path_file_photo_principale[0].image_miniature[2]"
                        class="product-miniature"
                    >

                    <div class="product-btn">
                        <img
                            id="show-modal"
                            :data-target="'#myModal'+ produit.id_produit.id"
                            class="product-details"
                            data-toggle="modal"
                            src="/storage/bobby_images/marketplace-client/btn-view.svg"
                            @click="modalOpen(produit)"
                        >
                    </div>

                    <div class="out-of-stock">
                        <p>Victime de son succès</p>
                    </div>
                </div>

                <div class="col-xs-12 product-tile-name">
                    <h3>{{ produit.id_produit.nom }}</h3>
                </div>
            </div>

            <ModalWindows
                v-if="produit"
                v-show="showModal"
                ref="product"
                :id="'myModal'+ produit.id_produit.id"
                :item="produit"
                class="modal fade"
                role="dialog"
            />
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import ModalWindows from './ProductInfo';

export default {
    name: 'ProductTile',
    components: {
        ModalWindows
    },
    props: {
        produit: { type: Object, required: true }
    },
    data: () => {
        return {
            showModal: false
        };
    },
    computed: {
        ...mapState({
            ShopStatus: state => state.ClientStore.shop_info
        })
    },
    destroyed () {
        const modalBackDrop = document.getElementsByClassName('modal-backdrop');

        if (modalBackDrop.length > 0) {
            modalBackDrop[0].remove();
        }

        this.showModal = false;
    },
    methods: {
        epuiser (produit) {
            let aff = true;
            for (let i = 0; i < produit.id_produit.stocks.length; i++) {
                if (produit.id_produit.stocks[i].activer && produit.id_produit.stocks[i].afficher) {
                    aff = false;
                }
            }

            return aff;
        },
        modalOpen (produit, model) {
            this.$store.commit('SET_PRODUCT_ID_FOR_CLIENT', produit.id_produit.id);
            this.$store.commit('SET_PRODUCT_IMG_FOR_PANIER', produit.id_produit.path_file_photo_principale);
            this.$store.commit('SET_PRODUCT_NAME_FOR_PANIER', produit.id_produit.nom);
            this.$store.commit('SET_SELECT_PRODUIT_DETAIL', produit);
            this.$store.commit('SET_SELECT_IMG', produit.id_produit.path_file_photo_principale[0].image_miniature[2]);

            if (model) {
                this.$store.commit('SET_PRODUCT_MODEL_FOR_PANIER', model);
            }

            this.$store.commit('SET_PRODUCT_QUANTITE_FOR_PANIER', 1);

            this.showModal = true;
        },
        addProduct (produit, model) {
            this.$store.commit('SET_SELECT_PRODUIT_DETAIL', produit);
            this.$store.commit('SET_SELECT_IMG', produit.id_produit.path_file_photo_principale[0].image_miniature[2]);
            this.$store.commit('SET_PRODUCT_ID_FOR_CLIENT', produit.id_produit.id);
            this.$store.commit('SET_PRODUCT_IMG_FOR_PANIER', produit.id_produit.path_file_photo_principale);
            this.$store.commit('SET_PRODUCT_NAME_FOR_PANIER', produit.id_produit.nom);
            this.$store.commit('SET_PRODUCT_MODEL_FOR_PANIER', model);
            this.$store.commit('SET_PRODUCT_QUANTITE_FOR_PANIER', 1);

            if (produit.id_produit.stocks.length === 1) {
                this.$store.commit('ADD_TO_PANIER');
                this.$store.commit('ADD_TO_GENERAL_PANIER');
                this.$store.dispatch('GET_GENERAL_INFORMATIONS');
            } else {
                this.showModal = true;
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

#ProductTile {
    img {
        margin: 0;
        width: 100%;
        cursor: pointer;
    }

    h3 {
        @include font-size(1.3);
        font-weight: 500;
        margin-bottom: 0;
    }

    h2 {
        @include font-size(1.2);
        color: $dark-grey;
        font-weight: 500;
        margin-top: 10px;
    }

    .product-tile-image {
        padding: 0;
        width: 100%;
        height: 100%;

        img {
            border-radius: 4px;
        }

        .product-btn {
            transition: .5s ease;
            opacity: 0;
            display: flex;
            justify-content: center;
            height: 100%;
            width: 100%;
            transform: none;
            position: absolute;
            top: 0;
            left: 0;

            img {
                padding: 15px;

                @media only screen and (max-width: 600px) {
                    padding: 5px;
                }
            }

            .quick-add-to-cart:active {
                transform: translateY(4px);
            }
        }

        .out-of-stock {
            width: 100%;
            height: 25px;
            position: absolute;
            bottom: 0;
            border-radius: 0 0 4px 4px;
            background-color: #FF6600;
            text-align: center;

            p {
                margin-top: 3px;
                color: #FFDAC1;
                font-size: 14px;
            }
        }

        &:hover .product-btn {
            opacity: 1;

            img {
                cursor: pointer;
            }
        }

        &:hover .product-miniature {
            opacity: .5;
        }
    }

    .product-tile-name {
        padding: 0;

        h3 {
            display: block;
            display: -webkit-box;
            max-width: 100%;
            height: 26px;
            font-size: 13px;
            line-height: 1;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }

    .product-out-of-stock {
        h3 {
            padding-bottom: 33px;
        }

        img {
            opacity: .6;
        }

        .product-btn {
            img {
                width: 50%;
                opacity: 1;
            }
        }
    }
}
</style>
