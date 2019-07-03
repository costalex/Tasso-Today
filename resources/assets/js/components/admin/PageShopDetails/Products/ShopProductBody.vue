<template>
    <div id="ShopProduits">
        <div
            id="HeadProduits"
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
            style="padding-top: 20px; padding-bottom: 20px;"
        >
            <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                <autocomplete
                    v-model="searchQuery"
                    :activer_choix="false"
                    :suggestions="ProduitsShop"
                    value_palceholder="Rechercher un produit"
                />
            </div>
            <div class="col-md-1 col-sm-1 col-xs-1 no-padding">
                <p style="width: 180px; margin-top: 5px; margin-left: 20px;">
                    Produits : {{ ProduitsShop.length }}
                </p>
            </div>
        </div>
        <div id="CoreProduits">
            <Tableau
                :typeligne="typeligne"
                :data="ProduitsShop"
                :columns="gridColumns"
                :filter-key="searchQuery"
                class="table-responsive-admin"
            />
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Tableau from '@/components/Table';
import Autocomplete from '@/components/Autocomplete';

export default {
    name: 'BodyShopDetails',
    components: {
        Tableau,
        Autocomplete
    },
    data: () => {
        return {
            typeligne: 'Produit',
            select_famille: { id: 0, nom: 'Familles' },
            select_categorie: { id: 0, nom: 'Categories' },
            select_type: { id: 0, nom: 'Types' },
            searchQuery: '',
            gridColumns: [
                { key: 'path_file_photo_principale', value: '' },
                { key: 'nom', value: 'Nom produit' },
                { key: 'ref_produit', value: 'Référence' },
                { key: 'marque', value: 'Marque' },
                { key: 'updated_at', value: 'Modifié' },
                { key: 'delete_shop', value: '' }
            ]
        };
    },
    computed: {
        ...mapState({
            TypeAccount: state => state.GeneralModule.TypeUser,
            Familles: state => state.SelectTabModule.Families,
            Categories: state => state.SelectTabModule.Categories,
            Typeproduits: state => state.SelectTabModule.Type,
            ProduitsShop: state => state.DetailsShopModule.enseigne_produit
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
        },
        select_categorie: function (value) {
            if (value.id !== 0) {
                this.select_categorie = value;
                this.$store.commit('SET_SELECT_CATEGORIE', value.id);
                this.$store.commit('SET_SELECT_TYPE', 0);
                this.select_type = { id: 0, nom: 'Types' };
                this.$store.dispatch('INITIALIZE_LIST_TYPE', value.id);
            }
        },
        select_type: function (value) {
            this.select_type = value;
            this.$store.commit('SET_SELECT_TYPE', value.id);
        }
    },
    beforeDestroy: function () {
        if (this.TypeAccount === 'Admin') {
            this.$store.commit('SET_SELECT_FAMILLE', 0);
            this.$store.commit('SET_SELECT_CATEGORIE', 0);
            this.$store.commit('SET_SELECT_TYPE', 0);
            this.$store.commit('SET_SELECT_NAME', '');
        }
    }
};
</script>
