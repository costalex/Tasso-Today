<template>
    <div id="ListProduit">
        <div
            id="HeadBoddy"
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
            style="padding-bottom: 20px;"
        >
            <div
                class="col-md-3 col-sm-3 col-xs-3 no-padding"
                style="width: 190px"
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
                class="col-md-3 col-sm-3 col-xs-3 no-padding"
                style="width: 190px"
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
                class="col-md-3 col-sm-3 col-xs-3 no-padding"
                style="width: 190px"
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
            <div class="col-md-2 col-sm-2 col-xs-2 no-padding">
                <autocomplete
                    v-model="select_name"
                    :activer_choix="false"
                    :suggestions="Produits"
                    value_palceholder="Rechercher un produit"
                />
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                <p style="width: 180px; margin-top: 5px; margin-left: 20px;">
                    Produits : {{ Produits.length }}
                </p>
            </div>
        </div>
        <div
            id="CoreBoddy"
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
        >
            <Tableau
                :typeligne="typeligne"
                :data="Produits"
                :columns="gridColumns"
                :filter-key="select_name"
                class="table-responsive-admin"
            />
        </div>

        <button @click="Prev_page">
            &#60; Prev
        </button>

        <button @click="Next_page(Produits)">
            Next &#62;
        </button>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Tableau from '@/components/Table';
import Autocomplete from '@/components/Autocomplete';

export default {
    name: 'ProductList',
    components: {
        Tableau,
        Autocomplete
    },
    data: () => {
        return {
            typeligne: 'Produit',
            select_famille: { id: 0, nom: 'Familles' },
            select_categorie: { id: 0, nom: 'Categories' },
            select_name: '',
            select_type: { id: 0, nom: 'Types' },
            gridColumns: [
                { key: 'path_file_photo_principale', value: '' },
                { key: 'nom', value: 'Nom produit' },
                { key: 'ref_produit', value: 'Référence' },
                { key: 'marque', value: 'Marque' },
                { key: 'action', value: 'Action' }
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
            Produits: state => state.ProductModule.produits
        })
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
        },
        select_type: function (value) {
            this.select_type = value;
            this.$store.commit('SET_SELECT_TYPE', value.id);
            this.$store.dispatch('GET_PRODUITS');
            this.Reset_pagination();
        },
        select_name: function (value) {
            this.$store.commit('SET_SELECT_NAME', value);
            this.$store.dispatch('GET_PRODUITS');
            this.Reset_pagination();
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
    },
    beforeDestroy: function () {
        if (this.TypeAccount === 'Admin') {
            this.$store.commit('SET_SELECT_FAMILLE', 0);
            this.$store.commit('SET_SELECT_CATEGORIE', 0);
            this.$store.commit('SET_SELECT_TYPE', 0);
            this.$store.commit('SET_SELECT_NAME', '');
            this.$store.dispatch('GET_PRODUITS');
        }
    },
    methods: {
        Reset_pagination: function () {
            this.page_num = 0;
            this.$store.commit('SET_PAGE_ID', 0);
            this.$store.commit('RESET_TAB_SAVE');
            this.$store.dispatch('GET_PRODUITS');
        },
        Next_page: function (tab) {
            this.page_num++;
            this.$store.commit('SET_PAGE_ID', tab[tab.length - 1].id);
            this.$store.commit('SET_TAB_SAVE', {
                page_num: this.page_num,
                id_produit: this.$store.getters.getPageId
            });
            this.$store.dispatch('GET_PRODUITS');
        },
        Prev_page: function () {
            if (this.page_num > 0) {
                this.page_num--;
                var tab = this.$store.getters.getTabSave;
                this.$store.commit('SET_PAGE_ID', tab[this.page_num].id_produit);
                this.$store.dispatch('GET_PRODUITS');
            }
        }
    }
};
</script>
