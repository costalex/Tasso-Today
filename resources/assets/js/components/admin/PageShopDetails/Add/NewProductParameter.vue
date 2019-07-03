<template>
    <div
        id="NewProduitsParameter"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding">
            <div
                class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding"
                align="left"
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
                                :key="option.id"
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
                            :value_palceholder="'MARQUES'"
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
    name: 'NewProduitParameter',
    components: { Autocomplete },
    data: () => {
        return {
            select_index: 'PUBLIC',
            select_marque: '',
            select_unites: 'UNITE',
            select_famille: { id: 0, nom: 'Familles' },
            select_categorie: { id: 0, nom: 'Categories' },
            select_type: { id: 0, nom: 'Types' }
        };
    },
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
        }
    },
    watch: {
        select_famille: function (val) {
            this.select_famille = val;
            this.$store.commit('SET_NEW_FAMILLE', val.id);
            this.select_categorie = { id: 0, nom: 'Categories' };
            this.select_type = { id: 0, nom: 'Types' };
            this.$store.dispatch('INITIALIZE_LIST_CATEGORIE', val.id);
            this.$store.dispatch('INITIALIZE_LIST_TYPE', 0);
        },
        select_categorie: function (val) {
            if (val.id !== 0) {
                this.select_categorie = val;
                this.$store.commit('SET_NEW_CATEGORIE', val.id);
                this.select_type = { id: 0, nom: 'Types' };
                this.$store.dispatch('INITIALIZE_LIST_TYPE', val.id);
            }
        },
        select_type: function (val) {
            if (val.id !== 0) {
                this.select_type = val;
                this.$store.commit('SET_NEW_TYPE', val.id);
            }
        },
        select_unites: function (val) {
            this.select_unites = val;
            this.$store.commit('SET_NEW_UNITES', val);
        },
        select_marque: function (val) {
            this.select_marque = val.toUpperCase();
            this.$store.commit('SET_NEW_MARQUE', val.toUpperCase());
            if (val !== '') {
                this.$store.dispatch('INITIALIZE_LIST_MARQUE', val.toUpperCase());
            } else {
                this.$store.commit('SET_RESET_MARQUE');
            }
        },
        select_index: function (val) {
            this.select_index = val;
            this.$store.commit('SET_NEW_INDEX', val);
        }
    },
    mounted: function () {
        this.$store.commit('SET_NEW_UNITES', 'UNITE');
        this.$store.dispatch('INITIALIZE_LIST_FAMILLE');
        this.$store.dispatch('INITIALIZE_LIST_CATEGORIE', 0);
        this.$store.dispatch('INITIALIZE_LIST_TYPE', 0);
        this.$store.dispatch('INITIALIZE_LIST_MARQUE', 'MARQUE');
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
