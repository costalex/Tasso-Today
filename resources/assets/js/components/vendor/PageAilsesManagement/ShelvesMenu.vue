<template>
    <div>
        <div class="col-xs-12 no-padding">
            <div class="col-xs-2 no-padding">
                <button
                    class="btn btn-back-shelf"
                    @click="BackRayons"
                >
                    <i
                        class="fa fa-long-arrow-left"
                        aria-hidden="true"
                    />
                    Rayons
                </button>
            </div>

            <div class="col-xs-10 shelf-infos no-padding">
                <div>{{ RayonsName }} > {{ SousRayonsName }} > {{ EtagereName }}</div>
            </div>
        </div>

        <div class="col-xs-12 no-padding drag">
            <div class="col-xs-3 list-product">
                <div
                    v-for="element in Produits"
                    id="element"
                    :key="element.id"
                    draggable="true"
                    class="product-element"
                    @dragstart="setProduitDrop(element)"
                    @touchstart="touchStart(element)"
                    @touchmove="touchMove($event)"
                    @touchend="touchEnd"
                >
                    <draggable >
                        <img :src="element.path_file_photo_principale[0].image_miniature[1]" >
                    </draggable>

                    <p>{{ element.nom }}</p>
                </div>
            </div>

            <div
                id="top"
                class="col-xs-9 no-padding product-shelf"
            >
                <div
                    v-for="(element, index) in ProduitsEtagere"
                    :key="index"
                >
                    <div
                        @dragover.prevent
                        @drop="drop(index)"
                    >
                        <div
                            v-if="ProduitsEtagere[index].id_produit"
                            :id="index"
                            :style="`left: ${ProduitsEtagere[index].position.left}; top: ${ProduitsEtagere[index].position.top}; position: absolute;`"
                            class="col-xs-2 no-padding"
                        >
                            <div
                                :id="index"
                                class="col-xs-10 no-padding"
                            >
                                <img
                                    v-if="ProduitsEtagere[index].id_produit.path_file_photo_principale"
                                    :id="index"
                                    :src="ProduitsEtagere[index].id_produit.path_file_photo_principale[0].image_miniature[1]"
                                    draggable="false"
                                >

                                <p :id="index">
                                    {{ ProduitsEtagere[index].id_produit.nom }}
                                </p>

                                <p
                                    v-if="epuiser(ProduitsEtagere[index].id_produit.stocks) !== 'Epuisé'"
                                    :id="index"
                                >
                                    {{ (epuiser(ProduitsEtagere[index].id_produit.stocks) * 1).toFixed(2).replace('.', ',') }} €
                                </p>

                                <p
                                    v-else
                                    :id="index"
                                >
                                    Epuisé
                                </p>
                            </div>

                            <div class="col-xs-2 no-padding">
                                <span
                                    class="remove-product"
                                    @click="deleteProduct(ProduitsEtagere[index].id_produit, index)"
                                >
                                    <i
                                        class="fa fa-times-circle-o fa-2x"
                                        aria-hidden="true"
                                    />
                                </span>
                            </div>
                        </div>

                        <div
                            v-else
                            :id="index"
                            :style="`left: ${ProduitsEtagere[index].position.left}; top: ${ProduitsEtagere[index].position.top}; position: absolute;`"
                            class="col-xs-2 no-padding drag-product"
                        >
                            <span
                                :id="index"
                                class="col-xs-12 no-padding"
                            >
                                <i
                                    :id="index"
                                    class="fa fa-file-image-o fa-3x"
                                    aria-hidden="true"
                                />
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import draggable from 'vuedraggable';

export default {
    name: 'ShelvesMenu',
    components: {
        draggable
    },
    data: () => {
        return {
            select_categorie: 'Categories',
            produit_drop: {},
            savelength: 0,
            ProduitEtagerSave: [],
            save_index: -1
        };
    },
    computed: {
        ...mapState({
            TypeAccount: state => state.GeneralModule.TypeUser,
            ProduitsEtagere: state => state.ShelfModule.produits
        }),
        Categories: {
            get () {
                return this.$store.getters.getCategories;
            }
        },
        Produits: {
            get () {
                return this.$store.getters.getEnseigneProduit;
            }
        },
        RayonsName: {
            get () {
                return this.$store.getters.getRayonsdNom;
            }
        },
        SousRayonsName: {
            get () {
                return this.$store.getters.getSousRayonsNom;
            }
        },
        EtagereName: {
            get () {
                return this.$store.getters.getEtagereNom;
            }
        }

    },
    watch: {
        select_categorie (val) {
            this.select_categorie = val;
        },
        ProduitsEtagere () {
            this.ProduitEtagerSave = this.ProduitsEtagere;
        }
    },
    beforeMount () {
        this.$store.dispatch('SET_RAYONS_VENDEUR');
        this.ProduitEtagerSave = this.$store.getters.getProduitsEtagere;
    },
    mounted () {
        this.ProduitEtagerSave = this.$store.getters.getProduitsEtagere;
        this.savelength = this.ProduitEtagerSave.length;
    },
    beforeDestroy () {
        if (this.TypeAccount === 'Vendeur') {
            this.$store.commit('REST_ETAGERE');
            this.ProduitEtagerSave = [];
            this.$store.dispatch('SET_RAYONS_VENDEUR');
        }
    },
    methods: {
        epuiser (stocks) {
            let aff = 'Epuisé';
            for (let stock = 0; stock in stocks; stock++) {
                if (stocks[stock].activer && stocks[stock].afficher) {
                    aff = stocks[stock].prix.toString();
                }
            }

            return aff;
        },
        touchStart (element) {
            this.produit_drop = element;
        },
        touchEnd () {
            if (this.save_index !== -1) {
                let addProduct = false;
                for (let i = 0; i < this.ProduitEtagerSave.length; i++) {
                    if (i === this.save_index) {
                        this.ProduitEtagerSave[i].id_produit = this.produit_drop.id;
                        addProduct = true;
                    }
                }

                if (!addProduct && this.ProduitEtagerSave.length < 11) {
                    this.ProduitEtagerSave.push(this.produit_drop);
                }

                this.save_index = -1;
                this.$store.commit('SET_PRODUITS_ETAGERE', this.ProduitEtagerSave);
                this.$store.dispatch('ADD_PRODUIT_TO_ETAGERE', this.ProduitEtagerSave);
            }
        },
        touchMove (event) {
            const x = event.targetTouches[0].clientX;
            const y = event.targetTouches[0].clientY;
            const hoveredElement = document.elementFromPoint(x, y);

            if (hoveredElement && hoveredElement.id) {
                if (hoveredElement.id === 'top') {
                    this.save_index = -1;
                } else {
                    const idBalise = Number.parseInt(hoveredElement.id);

                    if (idBalise >= 0 && idBalise < 12) {
                        this.save_index = idBalise;
                    }
                }
            }
        },
        BackRayons () {
            this.$emit('back-to-ailses');
        },
        deleteProduct (value, index) {
            this.$store.dispatch('DELETE_PRODUIT_ETAGERE', { produit: value, id_position: index });
        },
        drop (index) {
            let addProduct = false;
            for (let i = 0; i < this.ProduitEtagerSave.length; i++) {
                if (i === index) {
                    this.ProduitEtagerSave[i].id_produit = this.produit_drop.id;
                    addProduct = true;
                }
            }

            if (!addProduct && this.ProduitEtagerSave.length < 11) {
                this.ProduitEtagerSave.push(this.produit_drop);
            }

            this.save_index = -1;
            this.$store.commit('SET_PRODUITS_ETAGERE', this.ProduitEtagerSave);
            this.$store.dispatch('ADD_PRODUIT_TO_ETAGERE', this.ProduitEtagerSave);
        },
        setProduitDrop (item) {
            this.produit_drop = item;
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.shelf-infos {
    @include prefix(border-radius, 5px, webkit moz ms o);
    background-color: $light-grey;
    border: 1px solid $grey;
    margin-bottom: 30px;
}

.btn-back-shelf {
    @include font-size(1.5);
    align-items: center;
    align-self: center;
    background-color: $white;
    color: $secondary-color;
    display: inline-block;
    font-family: $default-font-family;
    font-weight: 400;
    text-align: center;
    text-decoration: none;

    &:hover {
        color: $primary-color;
    }
}

.list-product {
    max-height: 90vh;
    overflow-y:scroll;
    padding-left: 0;
    /*padding-right: 5px;*/

    .product-element {
        display: inline-block;
        width: 50%;
        text-align: center;
        padding: 0 2px;

        img {
            width: 100%;
        }
    }
}

.list-product::-webkit-scrollbar {
    width: 20px;
}

.list-product::-webkit-scrollbar-track {
    box-shadow: inset 0 0 5px grey;
    border-radius: 10px;
}

.list-product::-webkit-scrollbar-thumb {
    background-color: #CCCCCC;
    border-radius: 10px;
}

.product-shelf {
    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
    border: 1px solid $grey;
    background-color: #FFFFFF;
    height: 550px;
    position: relative;
    .remove-product {
        background-color: Transparent;
        border: none;
        color: $secondary-color;
        cursor:pointer;
        overflow: hidden;
        outline:none;
        padding-left: 5px;
    }

    .drag-product {
        background-color: $light-grey;
        border: 2px dashed $secondary-color;
        height: 100px;
        text-align: center;
        width: 100px;
        span {
            padding-top: 20%;
        }
    }
}
</style>
