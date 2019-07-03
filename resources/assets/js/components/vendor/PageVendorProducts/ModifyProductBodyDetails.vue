<template>
    <div
        id="ModifProduitDetails"
        class="col-md-12 col-sm12 col-xs-12 no-padding"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding product-dimensions">
            <div
                class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding"
                style="padding-left: 15px;"
            >
                <h4>
                    Prix & Stocks <span> *Incluant, le cas échéant, son emballage</span>
                </h4>
            </div>

            <div class="panel-body col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <p style="text-align: center;">
                            ID
                        </p>
                    </div>

                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <p>Modèle</p>
                    </div>

                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <p>Couleur</p>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding">
                        <p>Prix de vente</p>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                        <p>Dimensions (en cm)*</p>
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                <p>longueur</p>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                <p>largeur</p>
                            </div>

                            <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                <p>hauteur</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <p>Poids (g)*</p>
                    </div>

                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <p>Stock</p>
                    </div>

                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <p>Activer</p>
                    </div>
                </div>

                <div
                    v-for="(produit, i) in obj_stock"
                    :key="produit.id"
                    class="col-md-12 col-sm-12 col-xs-12 no-padding"
                >
                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding">
                        <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                            <p
                                class="no-margin"
                                style="text-align: center; margin-top: 5px;"
                            >
                                {{ produit.id }}
                            </p>
                        </div>

                        <div class="col-md-6 col-sm-6 cl-xs-6 no-padding">
                            <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                <input
                                    id="Model"
                                    v-model="produit.model"
                                    type="text"
                                    @change="updateStock(produit, i)"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <select
                            v-model="produit.couleur"
                            :style="{ backgroundColor: produit.couleur.code_hexa }"
                            class="fa-select"
                            @change="updateStock(produit, i)"
                        >
                            <option
                                v-for="(option, index) in Couleurs"
                                :key="index"
                                :value="option"
                                :style="{ backgroundColor: option.code_hexa }"
                            >
                                : {{ option.nom }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-2 col-xs-2 no-padding">
                        <input
                            id="prix"
                            v-model.number="produit.prix"
                            type="number"
                            min="0"
                            @change="updateStock(produit, i)"
                        >
                        €
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                        <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                            <input
                                id="longueur"
                                v-model.number="produit.longueur"
                                type="number"
                                min="0"
                                @change="updateStock(produit, i)"
                            >
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                            <input
                                id="largeur"
                                v-model.number="produit.largeur"
                                type="number"
                                min="0"
                                @change="updateStock(produit, i)"
                            >
                        </div>

                        <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                            <input
                                id="hauteur"
                                v-model.number="produit.hauteur"
                                type="number"
                                min="0"
                                @change="updateStock(produit, i)"
                            >
                        </div>
                    </div>

                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <input
                            id="poids"
                            v-model.number="produit.poids"
                            type="number"
                            min="0"
                            style="width: 70px;"
                            @change="updateStock(produit, i)"
                        >
                    </div>

                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <input
                            id="stock"
                            v-model.number="produit.stock"
                            type="number"
                            min="0"
                            @change="updateStock(produit, i)"
                        >
                    </div>

                    <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                        <div class="col-md-6 col-sm-6 col-xs-6 no-padding activate-product">
                            <label class="label">
                                <input
                                    v-model="produit.activer"
                                    class="label__checkbox"
                                    type="checkbox"
                                    @change="updateStock(produit, i)"
                                >

                                <span class="label__text">
                                    <span class="label__check">
                                        <i class="fa fa-check icon" />
                                    </span>
                                </span>
                            </label>
                        </div>

                        <div class="btn-delete-model col-md-6 col-sm-6 col-xs-6 no-padding">
                            <span @click="deleteStock(i)">
                                <i
                                    class="fa fa-times-circle fa-2x"
                                    aria-hidden="true"
                                />
                            </span>
                        </div>
                    </div>
                </div>

                <div class="container col-md-12 col-sm-12 col-xs-12 no-padding">
                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                        <button
                            class="btn add-model"
                            @click="addStock(obj_stock.length)"
                        >
                            <i
                                class="fa fa-plus-circle"
                                aria-hidden="true"
                            />
                            Ajouter un modèle
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ModifyProductBodyDetails',
    data: () => {
        return {
            obj_stock: []
        };
    },
    computed: {
        Models: {
            get () {
                return this.$store.getters.getModifProduitModels;
            }
        },
        Couleurs: {
            get () {
                return this.$store.getters.getModifProduitCouleurs;
            }
        }
    },
    mounted: function () {
        this.$store.dispatch('SET_SELECT_COLOR');
        this.obj_stock = this.$store.getters.getProduitStock;
    },
    methods: {
        updateStock (produit, index) {
            const produitVolume = produit.longueur * produit.largeur * produit.hauteur;

            if (produit.stock <= 0 ||
                produit.poids <= 0 ||
                produit.longueur <= 0 ||
                produit.largeur <= 0 ||
                produit.hauteur <= 0 ||
                produit.model === '' ||
                produitVolume > 39000 ||
                produit.poids > 13000
            ) {
                produit.activer = false;
                produit.afficher = false;
                this.obj_stock[index] = produit;
            }

            this.$store.commit('SET_MODIF_PRODUIT_DATA', this.obj_stock);
        },
        addStock (id) {
            this.obj_stock.push({
                activer: false,
                afficher: false,
                couleur: { nom: 'Aucune', code_hexa: '' },
                hauteur: 0,
                id: id,
                largeur: 0,
                longueur: 0,
                model: '',
                poids: 0,
                prix: 0,
                stock: 0
            });

            this.$store.commit('SET_MODIF_PRODUIT_DATA', this.obj_stock);
        },
        deleteStock (i) {
            this.obj_stock.splice(i, 1);

            let id = 0;
            for (let item in this.obj_stock) {
                this.obj_stock[item].id = id;
                id++;
            }

            this.$store.commit('SET_MODIF_PRODUIT_DATA', this.obj_stock);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.product-dimensions {
    .panel-heading {
        h4 > span {
            @include font-size(1.2);
            font-style: italic;
            font-weight: 300;
        }
    }

    .panel-body {
        padding-top: 20px;
        p {
            @include font-size(1.2);
            color: $secondary-color;
            margin-top: 10px;
        }
        input {
            @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
            border: 1px solid $grey;
            border-radius: 3px;
            width: 50px;
        }
        select {
            width: 50px;
        }

        .add-model {
            @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
            @include font-size(1.5);
            border-radius: 3px;
            background-color: $primary-color;
            margin: 30px 0 10px 15px;
            text-decoration: none;
            color: $white;
            i {
                padding-right: 5px;
                color: $white;
            }
            &:hover {
                @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                color: $white;
            }
        }
        .activate-product {
            position: absolute;
            top: 50%;
            left: 20%;
            transform: translate(-50%,-50%);

            .label__checkbox {
                display: none;
            }

            .label__check {
                border-radius: 50%;
                border: 5px solid rgba(0, 0, 0, 0.1);
                background: white;
                vertical-align: middle;
                margin-right: 20px;
                width: 2em;
                height: 2em;
                cursor: pointer;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: border .3s ease;

                i.icon {
                    opacity: 0.2;
                    color: transparent;
                    transition: opacity .3s .1s ease;
                    -webkit-text-stroke: 3px rgba(0, 0, 0, .5);
                }

                &:hover {
                    border: 5px solid rgba(0, 0, 0, 0.2);
                }
            }

            .label__checkbox:checked + .label__text .label__check {
                animation: check .5s cubic-bezier(0.895, 0.030, 0.685, 0.220) forwards;

                .icon {
                    opacity: 1;
                    transform: scale(0);
                    color: white;
                    -webkit-text-stroke: 0;
                    animation: icon .3s cubic-bezier(1.000, 0.008, 0.565, 1.650) .1s 1 forwards;
                }
            }

            @keyframes icon {
                from {
                    opacity: 0;
                    transform: scale(0.3);
                }
                to {
                    opacity: 1;
                    transform: scale(1)
                }
            }

            @keyframes check {
                0% {
                    width: 1.5em;
                    height: 1.5em;
                    border-width: 5px;
                }
                10% {
                    width: 1.5em;
                    height: 1.5em;
                    opacity: 0.1;
                    background: rgba(0, 0, 0, 0.2);
                    border-width: 15px;
                }
                12% {
                    width: 1.5em;
                    height: 1.5em;
                    opacity: 0.4;
                    background: rgba(0, 0, 0, 0.1);
                    border-width: 0;
                }
                50% {
                    width: 2em;
                    height: 2em;
                    background: $primary-color;
                    border: 0;
                    opacity: 0.6;
                }
                100% {
                    width: 2em;
                    height: 2em;
                    background: $primary-color;
                    border: 0;
                    opacity: 1;
                }
            }
        }

        .btn-delete-model {
            position: relative;
            top: 50%;
            left: 50%;

            span {
                color: #FB2E2E;
                cursor: pointer;
            }
        }
    }
}
</style>
