<template>
    <div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h2> {{ item.id_produit.nom }}</h2>

                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal"
                    >
                        <img src="/storage/bobby_images/marketplace-client/icon-cross.svg">
                    </button>
                </div>

                <div class="col-xs-12 no-padding product-infos">
                    <div
                        id="photo_fiche_produit"
                        class="col-sm-6 col-xs-12 product-info-container"
                    >
                        <img
                            id="main-picture"
                            :src="select_img"
                            class="img-responsive"
                        >

                        <div
                            v-if="item.id_produit"
                            id="product-pictures"
                        >
                            <div
                                v-for="(image, index) in item.id_produit.path_file_photo_principale"
                                :key="index"
                                class="sub-img"
                            >
                                <img
                                    :src="image.image_miniature[0]"
                                    @click="change_path(image)"
                                >
                            </div>

                            <div
                                v-for="(image, index) in item.id_produit.path_file_photos_secondaire"
                                v-if="image.image_miniature[0] !== null && image.image_miniature[0] !== ''"
                                :key="index"
                                class="sub-img"
                            >
                                <img
                                    :src="image.image_miniature[0]"
                                    @click="change_path(image)"
                                >
                            </div>
                        </div>

                        <div id="Information">
                            <h4>Infos produit</h4>

                            <p class="description-product">
                                {{ item.id_produit.description }}
                            </p>
                        </div>
                    </div>
                    <div
                        v-if="ShopStatus !== 'FERME_J' && !affQuantite"
                        class="col-sm-6 col-xs-12 product-action-container"
                    >
                        <div class="price">
                            <h3
                                v-for="(stock, index) in item.id_produit.stocks"
                                v-if="!bool_stock_select && stock.activer && stock.afficher"
                                :key="index"
                            >
                                {{ (stock.prix * 1).toFixed(2).replace('.', ',') }}€ / {{ stock.model }}
                            </h3>

                            <h3 v-if="bool_stock_select && model_select">
                                {{ (model_select.prix * 1).toFixed(2).replace('.', ',') }}€ / {{ model_select.model }}
                            </h3>
                        </div>

                        <div
                            id="quantiter"
                            class="block-quantite"
                        >
                            <h4>Quantité</h4>
                            <div class="add-quantity">
                                <div>
                                    <button
                                        class="btn btn-quantite"
                                        @click="sub_quantite"
                                    >
                                        <i
                                            class="fa fa-minus"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>

                                <div class="quantity-number">
                                    <p>{{ quantite }}</p>
                                </div>

                                <div>
                                    <button
                                        class="btn btn-quantite"
                                        @click="add_quantite"
                                    >
                                        <i
                                            class="fa fa-plus"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            id="lot"
                            class="batch-block"
                        >
                            <h4>Lots</h4>
                            <div class="batches-container row">
                                <div
                                    v-for="(stock, index) in item.id_produit.stocks"
                                    :key="index"
                                    class="batches col-xs-6"
                                >
                                    <button
                                        v-if="stock.activer"
                                        :class="{ 'model-selected': stock.model === model_select.model }"
                                        class="btn-lot"
                                        @click="set_stock_select(stock)"
                                    >
                                        {{ stock.model }}  {{ (stock.prix * 1).toFixed(2).replace('.', ',') }}€

                                        <span :style="'background-color: ' + stock.couleur.code_hexa + ';'">
                                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div
                            id="add"
                            class="add-product-basket"
                        >
                            <button
                                v-if="bool_stock_select && ShopStatus !== 'FERME_J'"
                                class="btn product-info-add-cart"
                                type="button"
                                data-dismiss="modal"
                                @click="add_item"
                            >
                                Ajouter au panier {{ (model_select.prix * quantite).toFixed(2).replace('.', ',') }}€
                            </button>

                            <div
                                v-for="(stock, index) in item.id_produit.stocks"
                                :key="index"
                            >
                                <button
                                    v-if="bool_stock_select === false && stock.activer && stock.afficher && ShopStatus !== 'FERME_J'"
                                    class="btn product-info-add-cart"
                                    type="button"
                                    data-dismiss="modal"
                                    @click="add_item"
                                >
                                    Ajouter au panier {{ (stock.prix * quantite).toFixed(2).replace('.', ',') }}€
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer" />
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'ModalWindows',
    props: {
        item: { type: Object, required: true }
    },
    data: () => {
        return {
            quantite: 1,
            stock_select: [],
            bool_stock_select: false,
            affQuantite: false
        };
    },
    computed: {
        ...mapState({
            model_select: state => state.CartModule.product.stocks,
            ShopStatus: state => state.ClientStore.shop_info.status
        }),
        select_img: {
            get () {
                return this.$store.getters.getSelectImgClient;
            }
        }
    },
    mounted () {
        this.affQuantite = this.epuiser(this.item);
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
        add_item () {
            this.$store.commit('SET_PRODUCT_QUANTITE_FOR_PANIER', this.quantite);

            if (this.stock_select) {
                this.$store.commit('ADD_TO_PANIER');
                this.$store.commit('ADD_TO_GENERAL_PANIER');
                this.$store.dispatch('GET_GENERAL_INFORMATIONS');
            } else {
                for (let i = 0; i < this.item.id_produit.stocks.length; i++) {
                    if (this.item.id_produit.stocks[i].activer && this.item.id_produit.stocks[i].afficher) {
                        this.$store.commit('SET_PRODUCT_MODEL_FOR_PANIER', this.item.id_produit.stocks[i]);
                        this.$store.commit('ADD_TO_PANIER');
                        this.$store.commit('ADD_TO_GENERAL_PANIER');
                        this.$store.dispatch('GET_GENERAL_INFORMATIONS');
                    }
                }
            }

            this.quantite = 1;
        },
        set_stock_select (stock) {
            this.bool_stock_select = true;
            this.quantite = 1;
            this.$store.commit('SET_PRODUCT_MODEL_FOR_PANIER', stock);
        },
        add_quantite () {
            if (this.quantite < this.model_select.stock) {
                this.quantite++;
                this.$store.commit('SET_PRODUCT_QUANTITE_FOR_PANIER', this.quantite);
            }
        },
        sub_quantite () {
            if (this.quantite > 1) {
                this.quantite--;
                this.$store.commit('SET_PRODUCT_QUANTITE_FOR_PANIER', this.quantite);
            }
        },
        change_path (value) {
            this.$store.commit('SET_SELECT_IMG', value.image_miniature[2]);
        }
    }
};
</script>
