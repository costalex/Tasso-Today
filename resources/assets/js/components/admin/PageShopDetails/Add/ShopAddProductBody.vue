<template>
    <div id="ShopAddProduits">
        <div v-if="!Newproduit">
            <div
                id="HeadAddProduits"
                class="col-md-12 col-sm-12 col-xs-12 no-padding"
                style="padding-top: 20px; padding-bottom: 20px;"
            >
                <div class="col-md-7">
                    <div
                        class="no-padding"
                        style="width: 190px; display: inline-block;"
                    >
                        <select
                            v-model="select_famille"
                            class="filter-picker"
                            style="width: 180px; margin-top: 4px;"
                        >
                            <option
                                v-for="option in Familles"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>
                    </div>

                    <div
                        class="no-padding"
                        style="width: 190px; display: inline-block;"
                    >
                        <select
                            v-model="select_categorie"
                            class="filter-picker"
                            style="width: 180px; margin-top: 4px;"
                        >
                            <option
                                v-for="option in Categories"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>
                    </div>

                    <div
                        class="no-padding"
                        style="width: 190px; display: inline-block;"
                    >
                        <select
                            v-model="select_type"
                            class="filter-picker"
                            style="width: 180px; margin-top: 4px;"
                        >
                            <option
                                v-for="option in Typeproduits"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <autocomplete
                        v-model="searchQuery"
                        :suggestions="AddProduitsShop"
                        :activer_choix="false"
                        value_palceholder="Rechercher un produit"
                    />
                </div>

                <div class="col-md-2 no-padding">
                    <button
                        class="btn btn-create-product"
                        @click="CreateProduit"
                    >
                        <i class="glyphicon glyphicon-plus-sign"/>
                        Nouveau produit
                    </button>
                </div>
            </div>

            <div
                id="CoreAddProduits"
                class="col-md-12 col-sm-12 col-xs-12 no-padding"
            >
                <Tableau
                    :typeligne="typeligne"
                    :data="AddProduitsShop"
                    :columns="gridColumns"
                    :filter-key="searchQuery"
                />

                <button @click="Prev_page">
                    &#60; Prev
                </button>

                <button @click="Next_page(AddProduitsShop)">
                    Next &#62;
                </button>
            </div>
        </div>

        <div v-else>
            <NewProduits />
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import NewProduits from './NewProductBody';
import Tableau from '@/components/Table';
import Autocomplete from '@/components/Autocomplete';

export default {
    name: 'ShopAddProduits',
    components: {
        Tableau,
        NewProduits,
        Autocomplete
    },
    data: () => {
        return {
            select_famille: { id: 0, nom: 'Familles' },
            select_categorie: { id: 0, nom: 'Categories' },
            select_type: { id: 0, nom: 'Types' },
            typeligne: 'NewProduct',
            searchQuery: '',
            gridColumns: [
                { key: 'path_file_photo_principale', value: '' },
                { key: 'nom', value: 'Nom produit' },
                { key: 'ref_produit', value: 'Référence' },
                { key: 'marque', value: 'Marque' },
                { key: 'have', value: 'Ajouter' }
            ],
            page_num: 0
        };
    },
    computed: {
        ...mapState({
            TypeAccount: state => state.GeneralModule.TypeUser,
            Familles: state => state.SelectTabModule.Families,
            Categories: state => state.SelectTabModule.Categories,
            Typeproduits: state => state.SelectTabModule.Type,
            AddProduitsShop: state => state.ProductModule.produits
        }),
        Newproduit: {
            get () {
                return this.$store.getters.getNewProduit;
            }
        }
    },
    watch: {
        select_famille: function (value) {
            this.select_famille = value;
            this.$store.commit('SET_SELECT_FAMILLE', value.id);
            this.$store.commit('SET_SELECT_CATEGORIE', 0);
            this.$store.commit('SET_SELECT_TYPE', 0);
            this.select_categorie = { id: 0, nom: 'Categories' };
            this.select_type = { id: 0, nom: 'Types' };
            this.$store.dispatch('INITIALIZE_LIST_CATEGORIE', value.id);
            this.$store.dispatch('INITIALIZE_LIST_TYPE', 0);
            this.$store.dispatch('GET_PRODUITS');
            this.$store.dispatch('SET_PRODUITS_HAVE');
            this.Reset_pagination();
        },
        select_categorie: function (value) {
            this.select_categorie = value;
            this.$store.commit('SET_SELECT_CATEGORIE', value.id);
            this.$store.commit('SET_SELECT_TYPE', 0);
            this.select_type = { id: 0, nom: 'Types' };
            this.$store.dispatch('INITIALIZE_LIST_TYPE', value.id);
            this.Reset_pagination();
            this.$store.dispatch('GET_PRODUITS');
            this.$store.dispatch('SET_PRODUITS_HAVE');
        },
        select_type: function (value) {
            this.select_type = value;
            this.$store.commit('SET_SELECT_TYPE', value.id);
            this.$store.dispatch('GET_PRODUITS');
            this.$store.dispatch('SET_PRODUITS_HAVE');
            this.Reset_pagination();
        },
        searchQuery: function (value) {
            this.$store.commit('SET_SELECT_NAME', value);
            this.$store.dispatch('GET_PRODUITS');
            this.Reset_pagination();
            this.$store.dispatch('SET_PRODUITS_HAVE');
            this.$store.commit('SET_SELECT_NAME', value);
        }
    },
    mounted: function () {
        if (this.$store.getters.getTypeUser === 'Admin') {
            this.$store.dispatch('GET_PRODUITS');
            this.$store.dispatch('INITIALIZE_LIST_FAMILLE');
            this.$store.dispatch('INITIALIZE_LIST_CATEGORIE', 0);
            this.$store.dispatch('INITIALIZE_LIST_TYPE', 0);
            this.$store.dispatch('GET_PRODUITS');
        }
        this.$store.commit('SET_PAGE_ID', 0);
        this.$store.dispatch('GET_PRODUITS');
        this.$store.dispatch('SET_PRODUITS_HAVE');
    },
    beforeDestroy: function () {
        if (this.TypeAccount === 'Vendeur') {
            this.Reset_pagination();
            this.$store.commit('SET_SELECT_NAME', '');
            this.$store.dispatch('GET_PRODUITS');
        } else if (this.TypeAccount === 'Admin') {
            this.$store.commit('SET_SELECT_FAMILLE', 0);
            this.$store.commit('SET_SELECT_CATEGORIE', 0);
            this.$store.commit('SET_SELECT_TYPE', 0);
            this.$store.commit('SET_SELECT_NAME', '');
            this.$store.dispatch('GET_PRODUITS');
        }
    },
    methods: {
        CreateProduit () {
            this.$store.commit('SET_NEWPRODUIT');
        },
        Reset_pagination: function () {
            this.page_num = 0;
            this.$store.commit('SET_PAGE_ID', 0);
            this.$store.commit('RESET_TAB_SAVE');
            this.$store.dispatch('GET_PRODUITS');
        },
        Next_page: function (tab) {
            this.page_num++;
            this.$store.commit('SET_PAGE_ID', tab[tab.length - 1].id);
            this.$store.commit('SET_TAB_SAVE', { page_num: this.page_num, id_produit: this.$store.getters.getPageId });
            this.$store.dispatch('GET_PRODUITS');
            this.$store.dispatch('SET_PRODUITS_HAVE');
        },
        Prev_page: function () {
            if (this.page_num > 0) {
                this.page_num--;
                const tab = this.$store.getters.getTabSave;
                this.$store.commit('SET_PAGE_ID', tab[this.page_num].id_produit);
                this.$store.dispatch('GET_PRODUITS');
                this.$store.dispatch('SET_PRODUITS_HAVE');
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.btn-create-product {
    @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
    @include font-size(1.4);
    align-items: center;
    align-self: center;
    background-color: $primary-color;
    border-radius: 5px;
    color: $white;
    display: inline-block;
    font-family: $default-font-family;
    font-weight: 500;
    text-align: center;
    text-decoration: none;

    &:hover {
        @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
        color: $white;
    }

    i {
        @include font-size(1.4);
        padding-right: 10px;
    }
}
</style>
