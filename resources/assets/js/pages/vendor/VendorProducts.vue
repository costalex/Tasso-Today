<template>
    <div id="Boddy">
        <div v-if="!Modifier">
            <div id="HeadBoddy">
                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                    <input
                        v-model="select_name"
                        class="form-control"
                        placeholder="Rechercher un produit"
                    >
                </div>

                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                    <p style="padding-left: 20px; padding-top: 10px">
                        Nombre de produits utilisé : {{ ProduitUse }}/{{ Object.values(Produits).length }}
                    </p>
                </div>
            </div>

            <div
                id="CoreBoddy"
                class="col-md-12 col-sm-12 col-xs-12 no-padding"
            >
                <Tableau
                    :typeligne="typeligne"
                    :data="Object.values(Produits)"
                    :columns="gridColumns"
                    :filter-key="select_name"
                    class="table-responsive-vendor"
                    order-key="nom"
                />
            </div>
        </div>

        <div v-else>
            <ModifProduit/>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Tableau from '@/components/Table';
import ModifProduit from '@/components/vendor/PageVendorProducts/ModifyProductBody';
import Autocomplete from '@/components/Autocomplete';

export default {
    name: 'VendorProductsBody',
    components: {
        Tableau,
        ModifProduit,
        Autocomplete
    },
    data: () => {
        return {
            typeligne: 'Produit_enseigne',
            gridColumns: [
                { key: 'path_file_photo_principale', value: '' },
                { key: 'nom', value: 'Nom produit' },
                { key: 'prix', value: 'prix' },
                { key: 'action', value: 'Action' }
            ]
        };
    },
    computed: {
        ...mapState({
            Produits: state => state.DetailsShopModule.enseigne_produit,
            ProduitUse: state => state.DetailsShopModule.enseigne_produit_use
        }),

        select_rayons: {
            set (value) {
                this.$store.commit('SET_SELECT_RAYONS', value);
            },
            get () {
                return this.$store.getters.getSelectRayons;
            }
        },
        select_sub_rayons: {
            set (value) {
                this.$store.commit('SET_SELECT_SUB_RAYONS', value);
            },
            get () {
                return this.$store.getters.getSelectSubRayons;
            }
        },
        select_etagere: {
            set (value) {
                this.$store.commit('SET_SELECT_ETAGERE', value);
            },
            get () {
                return this.$store.getters.getSelectEtagere;
            }
        },
        select_name: {
            set (value) {
                this.$store.commit('SET_SELECT_NAME', value);
            },
            get () {
                return this.$store.getters.getSelectName;
            }
        },
        Modifier: {
            set () {
                this.$store.commit('SET_MODIF_PRODUIT');
            },
            get () {
                return this.$store.getters.getModifProduit;
            }
        }
    },
    mounted: function () {
        this.$store.dispatch('SET_RAYONS_VENDEUR');
    }
};
</script>
