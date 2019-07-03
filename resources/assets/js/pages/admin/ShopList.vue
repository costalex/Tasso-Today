<template>
    <div id="ShopManageBody">
        <div id="Switch">
            <button @click="$router.push({ name: 'AdminVendorRegistration' })">
                Ajouter une enseigne
            </button>
        </div>

        <div id="ShopListBody">
            <div
                id="HeadBoddyShop"
                class="col-md-12 col-sm-12 col-xs-12 no-padding"
            >
                <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                    <select
                        v-model="select_ville"
                        class="filter-picker"
                    >
                        <option
                            v-for="option in Villes"
                            :key="option.id"
                            :value="option"
                        >
                            {{ option.nom }}
                        </option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                    <select
                        v-model="select_secteur_activite"
                        class="filter-picker"
                    >
                        <option
                            v-for="option in SecteurActivite"
                            :key="option.id"
                            :value="option"
                        >
                            {{ option.nom }}
                        </option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                    <select
                        v-model="select_statut"
                        class="filter-picker"
                    >
                        <option
                            v-for="option in Statut"
                            :key="option.id"
                            :value="option"
                        >
                            {{ option }}
                        </option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                    <autocomplete
                        v-model="select_search"
                        :suggestions="Shop"
                        :activer_choix="false"
                        value_palceholder="Rechercher Shop"
                    />
                </div>
            </div>

            <div
                id="CoreBoddyShop"
                class="col-md-12 col-sm-12 col-xs-12 no-padding"
            >
                <Tableau
                    :typeligne="typeligne"
                    :data="Shop"
                    :columns="gridColumns"
                    :filter-key="select_search"
                    order-key="nom_Shop"
                    class="table-responsive-admin"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Tableau from '@/components/Table';
import Autocomplete from '@/components/Autocomplete';

export default {
    name: 'ShopList',
    components: {
        Tableau,
        Autocomplete
    },
    data: () => {
        return {
            select_ville: { id: 0, nom: 'Villes' },
            select_secteur_activite: { id: 0, nom: 'Secteurs d\'activites' },
            select_statut: 'Status',
            typeligne: 'Enseigne',
            gridColumns: [
                { key: 'nom_enseigne', value: 'Nom Shop' },
                { key: 'id', value: 'Référence' },
                { key: 'ville', value: 'Ville' },
                { key: 'typeActivite', value: 'Secteur' },
                { key: 'status', value: 'Status' },
                { key: 'action', value: 'Action' }
            ],
            select_search: ''
        };
    },
    computed: {
        ...mapState({
            TypeAccount: state => state.GeneralModule.TypeUser,
            Shop: state => state.ShopModule.list_enseignes
        }),
        Villes: {
            get () {
                return this.$store.getters.getVilles;
            }
        },
        SecteurActivite: {
            get () {
                return this.$store.getters.getSecteurActivite;
            }
        },
        Statut: {
            get () {
                return this.$store.getters.getStatuts;
            }
        }
    },
    watch: {
        select_ville: function (val) {
            this.$store.commit('SET_SELECT_VILLE', val.id);
            this.$store.dispatch('INITIALIZE_ENSEIGNES');
        },
        select_secteur_activite: function (val) {
            this.$store.commit('SET_SELECT_SECTEUR_ACTIVITES', val.id);
            this.$store.dispatch('INITIALIZE_ENSEIGNES');
        },
        select_statut: function (val) {
            if (val === 'Status') {
                this.$store.commit('SET_SELECT_STATUT', '');
            } else {
                this.$store.commit('SET_SELECT_STATUT', val);
            }
            this.$store.dispatch('INITIALIZE_ENSEIGNES');
        },
        select_search: function (val) {
            this.$store.commit('SET_SELECT_ENSEIGNE_NAME', val);
            this.$store.commit('SET_ShopS');
        }
    },
    mounted: function () {
        this.$store.dispatch('INITIALIZE_LIST_VILLE');
        this.$store.dispatch('INITIALIZE_LIST_SECTEUR_ACTIVITE');
        this.$store.dispatch('INITIALIZE_ENSEIGNES');
    },
    beforeDestroy: function () {
        if (this.TypeAccount === 'Admin') {
            this.$store.commit('SET_SELECT_VILLE', 0);
            this.$store.commit('SET_SELECT_SECTEUR_ACTIVITES', 0);
            this.$store.commit('SET_SELECT_STATUT', 0);
            this.$store.commit('SET_SELECT_ENSEIGNE_NAME', '');
            this.$store.dispatch('INITIALIZE_ENSEIGNES');
        }
    }
};
</script>

<style lang="scss" scoped>
#HeadBoddyShop {
    padding-top: 10px;
    padding-bottom: 20px
}
</style>
