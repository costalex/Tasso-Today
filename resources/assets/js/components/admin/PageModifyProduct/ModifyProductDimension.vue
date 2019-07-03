<template>
    <div
        id="ModifyProductDimension"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding product-dimensions">
            <div
                class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding"
                style="padding-left: 15px;"
            >
                <h4>Dimensions et poids (unitaire)</h4>
            </div>

            <div
                class="panel-body col-md-12 col-sm-12 col-xs-12 no-padding"
                style="padding-bottom: 10px;"
            >
                <div
                    class="col-md-12 col-sm-12 col-xs-12 no-padding"
                    style="padding-left: 15px;"
                >
                    <h3>Volume du produit dans son emballage (en cm)</h3>
                </div>

                <div class="col-md-9 col-sm-9 col-xs-9 no-padding">
                    <div
                        class="col-md-3 col-sm-3 col-xs-3"
                        style="padding-top: 10px;"
                    >
                        <div class="panel-body col-md-12 col-sm-12 col-xs-12 no-padding">
                            <p>Hauteur</p>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <input
                                v-model="NewProduitHauteur"
                                type="number"
                                name="quantity"
                                min="1"
                                max="50"
                            >
                        </div>
                    </div>
                    <div
                        class="col-md-3 col-sm-3 col-xs-3"
                        style="padding-top: 10px;"
                    >
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <p>Longueur</p>
                        </div>

                        <div class="panel-body col-md-12 col-sm-12 col-xs-12 no-padding">
                            <input
                                v-model="NewProduitLongueur"
                                type="number"
                                name="quantity"
                                min="1"
                                max="50"
                            >
                        </div>
                    </div>
                    <div
                        class="col-md-3 col-sm-3 col-xs-3"
                        style="padding-top: 10px;"
                    >
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <p>Largeur</p>
                        </div>

                        <div class="panel-body col-md-10 col-sm-10 col-xs-10 no-padding">
                            <input
                                v-model="NewProduitLargeur"
                                type="number"
                                name="quantity"
                                min="1"
                                max="50"
                            >
                        </div>

                        <div class="panel-body col-md-2 col-sm-2 col-xs-2 no-padding">
                            <p>=</p>
                        </div>
                    </div>
                    <div
                        class="col-md-3 col-sm-3 col-xs-3"
                        style="padding-top: 10px;"
                    >
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <p>Volume (cm3)</p>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                            <p
                                id="product-volum"
                            >
                                {{ NewProduitVolume }}
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="col-md-3 col-sm-3 col-xs-3"
                    style="padding-top: 10px;"
                >
                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        <p>Poids (g)</p>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        <input
                            v-model="NewProduitPoids"
                            type="number"
                            name="quantity"
                            min="1"
                            max="50"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ModifyProductDimension',
    data: () => {
        return {
            dimention: ['hauteur', 'longueur', 'largeur']
        };
    },
    computed: {
        NewProduitHauteur: {
            set (value) {
                this.$store.commit('SET_NEW_HAUTEUR', value);
                this.$store.commit('SET_NEW_VOLUME');
            },
            get () {
                return this.$store.getters.getNewHauteur;
            }
        },
        NewProduitLongueur: {
            set (value) {
                this.$store.commit('SET_NEW_LONGUEUR', value);
                this.$store.commit('SET_NEW_VOLUME');
            },
            get () {
                return this.$store.getters.getNewLongueur;
            }
        },
        NewProduitLargeur: {
            set (value) {
                this.$store.commit('SET_NEW_LARGEUR', value);
                this.$store.commit('SET_NEW_VOLUME');
            },
            get () {
                return this.$store.getters.getNewLargeur;
            }
        },
        NewProduitVolume: {
            get () {
                return this.$store.getters.getNewVolume;
            }
        },
        NewProduitPoids: {
            set (value) {
                this.$store.commit('SET_NEW_POIDS', value);
            },
            get () {
                return this.$store.getters.getNewPoids;
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.product-dimensions {
    .panel-body {
        h3 {
            @include font-size(1.4);
            color: $dark-grey;
            margin-top: 10px;
        }
        input {
            border : 0;
            border-bottom: 1px solid $dark-grey;
        }
    }
}
</style>
