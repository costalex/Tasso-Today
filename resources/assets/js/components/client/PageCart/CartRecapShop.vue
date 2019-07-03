<template>
    <div
        v-if="Genral_information.entreprises"
        id="PanierShop"
        class="col-xs-12 no-padding recap-cart"
    >
        <div
            id="boutique-name"
            class="col-xs-12 no-padding"
        >
            <div class="col-xs-10 no-padding">
                <h1>{{ panier.shop.name }}</h1>
            </div>

            <div
                class="col-xs-1 no-padding"
                align="center"
                style="border-left: 1px solid #D8D8D8;"
            >
                <img
                    id="show-modal"
                    :data-target="'#' + modalId"
                    data-toggle="modal"
                    src="/storage/bobby_images/marketplace-client/icon-comment.svg"
                    @click="modalOpen"
                >
            </div>

            <div
                class="col-xs-1 no-padding"
                align="center"
                style="border-left: 1px solid #D8D8D8;"
            >
                <img
                    id="icon-delete-cart"
                    src="/storage/bobby_images/marketplace-client/icon-garbage.svg"
                    @click="deletePanier(panier.shop.name)"
                >
            </div>
        </div>

        <div
            v-for="product in panier.product"
            :key="product.id"
            class="col-md-12 col-sm-12 col-xs-12 no-padding product-line"
        >
            <div class="col-md-7 col-sm-7 col-xs-7 no-padding">
                <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                    <img :src="product.img[0].image_miniature[0]">
                </div>

                <div class="col-md-9 col-sm-9 col-xs-9 no-padding">
                    <p id="product-name">
                        {{ product.nom }}
                    </p>

                    <p id="model-product">
                        {{ product.stocks.model }}
                    </p>
                </div>
            </div>

            <div class="col-md-5 col-sm-5 col-xs-5 no-padding">
                <div
                    class="col-md-6 col-sm-6 col-xs-6 no-padding"
                    style="padding-top: 5px;"
                >
                    <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                        <button
                            class="btn btn-circle-sm badge sub-product"
                            @click="sub_quantite(panier.shop.name, product)"
                        >
                            <i
                                class="fa fa-minus"
                                aria-hidden="true"
                            />
                        </button>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                        <p style="padding: 4px 0 0 5px;">
                            {{ product.quantite }}
                        </p>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                        <button
                            class="btn btn-circle-sm badge add-product"
                            @click="add_quantite(panier.shop.name, product)">
                            <i
                                class="fa fa-plus"
                                aria-hidden="true"
                            />
                        </button>
                    </div>
                </div>

                <div
                    class="col-md-4 col-sm-4 col-xs-4 no-padding"
                    style="padding: 10px 0 0 8px;"
                >
                    <p>
                        {{ (product.quantite * product.stocks.prix).toFixed(2).replace('.', ',') }}€
                    </p>
                </div>

                <div
                    class="col-md-2 col-sm-2 col-xs-2 no-padding"
                    style="padding-top: 8px;"
                >
                    <button
                        class="btn delete-product"
                        @click="delete_product(panier.shop.name, product)"
                    >
                        <i
                            class="fa fa-times-circle fa-lg"
                            aria-hidden="true"
                        />
                    </button>
                </div>
            </div>
        </div>

        <div
            v-if="Livraison"
            class="col-xs-12 no-padding total-course"
        >
            <div class="col-xs-8 no-padding">
                <p class="line-title">
                    Nombre de course
                </p>
            </div>

            <div class="col-xs-4 no-padding">
                <div class="col-xs-5 no-padding">
                    <p>
                        {{ Genral_information.entreprises[panier.shop.name].nb_livraison_entreprise }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 no-padding total-shop">
            <div class="col-md-8 col-sm-8 col-xs-8 no-padding">
                <p class="line-title">
                    Total boutique
                </p>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                <div class="col-md-5 col-sm-5 col-xs-5 no-padding">
                    <p>{{ panier.quantite_total }}</p>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                    <p>
                        {{ (Genral_information.entreprises[panier.shop.name].total_montant_entreprise * 1).toFixed(2).replace('.', ',') }}€
                    </p>
                </div>
            </div>
        </div>

        <ModalCommentaire
            v-if="panier"
            v-show="showModal"
            :id="modalId"
            :shop-name="panier.shop.name"
            class="modal fade"
            role="dialog"
        />
    </div>
</template>

<script>
import { mapState } from 'vuex';
import ModalCommentaire from './CommentModal';

export default {
    name: 'PanierShop',
    components: {
        ModalCommentaire
    },
    props: {
        panier: { type: Object, required: true },
        modalId: { type: String, required: true }
    },
    data: () => {
        return {
            showModal: false
        };
    },
    computed: {
        ...mapState({
            Livraison: state => state.ClientOrderModule.livraison,
            Genral_information: state => state.ClientOrderModule.General_information,
            GeneralPanier: state => state.CartModule.panier_general,
            CookieAccepted: state => state.GeneralModule.CookieAccepted,
            TypeAccount: state => state.GeneralModule.TypeUser
        })
    },
    beforeDestroy () {
        if (this.TypeAccount === 'Vendeur' && this.CookieAccepted) {
            this.$session.set('panier', this.GeneralPanier);
        }
    },
    methods: {
        modalOpen () {
            this.showModal = true;
        },
        deletePanier (shopname) {
            this.$store.commit('DELETE_PANIER_BY_SHOP', shopname);
            this.$store.dispatch('GET_GENERAL_INFORMATIONS');
        },
        delete_product (shopname, product) {
            this.$store.commit('DELETE_PRODUCT_PANIER_GENERAL', {
                shopname: shopname,
                product: product
            });

            this.$store.dispatch('GET_GENERAL_INFORMATIONS');
        },
        sub_quantite (shopname, product) {
            this.$store.commit('DECREMENT_PRODUCT_PANIER_GENERAL', {
                shopname: shopname,
                product: product
            });

            this.$store.dispatch('GET_GENERAL_INFORMATIONS');
        },
        add_quantite (shopname, product) {
            this.$store.commit('INCREMENT_PRODUCT_PANIER_GENERAL', {
                shopname: shopname,
                product: product
            });

            this.$store.dispatch('GET_GENERAL_INFORMATIONS');
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.recap-cart {
    background-color: $white;

    #boutique-name {
        background-color: $light-grey;
        border-bottom: 1px solid $grey;
        border-top: 1px solid $grey;

        img {
            cursor: pointer;
        }

        h1 {
            @include font-size(1.4);
            font-weight: 500;
            margin: 0;
            padding: 8px 0 0 10px;
        }

        #icon-delete-cart {
            margin: 8px 0 5px 0;
            &:hover {
                content: url('/storage/bobby_images/marketplace-client/icon-garbage-hover.svg');
            }
        }

        #show-modal {
            margin: 8px 0 5px 0;
            &:hover {
                content: url('/storage/bobby_images/marketplace-client/icon-comment-hover.svg');
            }
        }
    }

    .product-line {
        margin: 10px 0 0 10px;

        img {
            border: 1px solid $grey;
        }

        p {
            @include font-size(1.3);
            padding: 0;
        }

        .add-product, .sub-product {
            @include font-size(1.2);
            @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
            background-color: $light-grey;

            i {
                color: $pastel-green;
                font-weight: 500;
            }

            &:hover {
                @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
            }
        }

        .delete-product {
            background-color: transparent;
            border: none;
            color: $dark-grey;
            padding: 0;

            &:hover {
                color: $red;
            }
        }

        #product-name {
            font-weight: 500;
            margin-bottom: 5px;
            padding-left: 10px
        }

        #model-product {
            color: $dark-grey;
            padding-left: 10px;
        }
    }

    .total-shop,
    .total-course {
        border-top: 1px solid $light-grey;

        p {
            @include font-size(1.5);
            font-style: italic;
        }

        .line-title {
            @include font-size(1.5);
            font-style: italic;
            padding-left: 5px;
        }
    }
}
</style>
