<template>
    <div
        id="ModifyProductParameter"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding">
            <div
                align="left"
                class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding"
                style="padding-left: 15px;"
            >
                <h4>Paramètres</h4>
            </div>

            <div class="panel-body col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                    <div class="col-md-3 col-sm-4 col-xs-3 no-padding">
                        <select
                            v-model="select_famille"
                            class="products-parameters-picker"
                        >
                            <option
                                v-for="option in FamillesProduits"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-4 col-xs-3 no-padding">
                        <select
                            v-model="select_categorie"
                            class="products-parameters-picker"
                        >
                            <option
                                v-for="option in CategoriesProduits"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 col-sm-4 col-xs-3 no-padding">
                        <select
                            v-model="select_type"
                            class="products-parameters-picker"
                        >
                            <option
                                v-for="option in TypesProduits"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>
                    </div>

                    <div
                        class="col-md-3 col-sm-6 col-xs-3 no-padding"
                        style="padding: 15px;"
                    >
                        <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                            <input
                                v-model="select_index"
                                type="radio"
                                value="PUBLIC"
                            >
                            Public
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                            <input
                                v-model="select_index"
                                type="radio"
                                value="PRIVE"
                            >
                            Privé
                        </div>
                    </div>
                </div>

                <div
                    class="col-md-12 col-sm-12 col-xs-12 no-padding"
                    style="padding-bottom: 20px;"
                >
                    <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                        <select
                            v-model="select_unites"
                            class="products-parameters-picker"
                        >
                            <option
                                v-for="option in UnitesProduits"
                                :key="option"
                                :value="option"
                            >
                                {{ option }}
                            </option>
                        </select>
                    </div>

                    <div
                        class="col-md-3 col-sm-3 col-xs-3 no-padding"
                        style="padding-top: 15px;"
                    >
                        <autocomplete
                            v-model="select_marque"
                            :activer_choix="true"
                            :suggestions="MarqueProduits"
                            value_palceholder="MARQUES"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Autocomplete from '@/components/Autocomplete';

export default {
    name: 'ModifyProductParameter',
    components: { Autocomplete },
    computed: {
        FamillesProduits: {
            get () {
                return this.$store.getters.getFamilles;
            }
        },
        CategoriesProduits: {
            get () {
                return this.$store.getters.getCategories;
            }
        },
        TypesProduits: {
            get () {
                return this.$store.getters.getType;
            }
        },
        UnitesProduits: {
            get () {
                return this.$store.getters.getUnites;
            }
        },
        MarqueProduits: {
            get () {
                return this.$store.getters.getMarques;
            }
        },
        select_famille: {
            set (value) {
                this.$store.commit('SET_NEW_FAMILLE', value.id);
                this.$store.dispatch('INITIALIZE_LIST_CATEGORIE', value.id);
                this.$store.dispatch('INITIALIZE_LIST_TYPE', 0);

                this.$store.commit('SET_NEW_CATEGORIE', 0);
                this.$store.commit('SET_NEW_TYPE', 0);
            },
            get () {
                let listfamille = this.$store.getters.getFamilles;
                let nom;
                for (let i in listfamille) {
                    if (listfamille[i].id === this.$store.getters.getNewProduitFamillie) {
                        nom = listfamille[i];
                    }
                }

                return nom;
            }
        },
        select_categorie: {
            set (value) {
                if (value) {
                    this.$store.commit('SET_NEW_CATEGORIE', value.id);
                    this.$store.dispatch('INITIALIZE_LIST_TYPE', value.id);

                    this.$store.commit('SET_NEW_TYPE', 0);
                }
            },
            get () {
                let listfamille = this.$store.getters.getCategories;
                let nom;
                for (let i in listfamille) {
                    if (listfamille[i].id === this.$store.getters.getNewProduitCategorie) {
                        nom = listfamille[i];
                    }
                }

                return nom;
            }
        },
        select_type: {
            set (value) {
                this.$store.commit('SET_NEW_TYPE', value.id);
            },
            get () {
                let listfamille = this.$store.getters.getType;
                let nom;
                for (let i in listfamille) {
                    if (listfamille[i].id === this.$store.getters.getNewProduitType) {
                        nom = listfamille[i];
                    }
                }

                return nom;
            }
        },
        select_unites: {
            set (value) {
                this.$store.commit('SET_NEW_UNITES', value);
            },
            get () {
                return this.$store.getters.getNNewProduitunites;
            }
        },
        select_index: {
            set (value) {
                this.$store.commit('SET_NEW_INDEX', value);
            },
            get () {
                return this.$store.getters.getNewProduitIndexation;
            }
        },
        select_marque: {
            set (value) {
                this.$store.commit('SET_NEW_MARQUE', value.toUpperCase());
                if (value !== '') {
                    this.$store.dispatch('INITIALIZE_LIST_MARQUE', value.toUpperCase());
                } else {
                    this.$store.commit('SET_RESET_MARQUE');
                }
            },
            get () {
                return this.$store.getters.getNewProduitMarque;
            }
        }
    },
    beforeMount: function () {
        this.$store.commit('SET_NEW_UNITES', 'UNITE');
        this.$store.dispatch('INITIALIZE_LIST_FAMILLE');
        this.$store.dispatch('INITIALIZE_LIST_MARQUE', 'MARQUE');
    },
    mounted: function () {
        this.select_marque = this.$store.getters.getNewProduitMarque;
        this.select_index = this.$store.getters.getNewProduitIndexation;
        this.select_unites = this.$store.getters.getNNewProduitunites;
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';

.products-parameters-picker {
    border: 1px solid $grey;
    border-radius: 5px;
    box-shadow: none;
    display: inline-block;
    margin: 15px 15px 0 15px;
    overflow: hidden;
    padding: 5px 8px;
    width: stretch;
}
</style>
