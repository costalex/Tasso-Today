<template>
    <div id="LandingPageClientConnect">
        <NavBar/>

        <div class="product-category-list-container">
            <h2>Catégories de commerces</h2>

            <TileProductCategoryList
                :categories="productCategoryListToShow"
                @click="searchShopFamille"
            />
        </div>

        <div
            v-for="famille in Famillies"
            :key="famille.id"
        >
            <div v-if="doesFamillyHasShops(famille)">
                <ProductCategoryShopList
                    :title="famille.nom"
                    :shops="Object.values(Shop[famille.id].shops)"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import NavBar from '@/components/client/ShoppingSettingsBar/ShoppingSettingsBar';
import ProductCategoryShopList from '@/components/client/ProductCategoryShopList';
import TileProductCategoryList from '@/components/client/Tile/TileProductCategoryList';

export default {
    name: 'FamilyList',
    components: {
        NavBar,
        ProductCategoryShopList,
        TileProductCategoryList
    },
    computed: {
        ...mapState({
            TypeAccount: state => state.GeneralModule.TypeUser,
            Shop: state => state.ShopModule.list_enseignes,
            CookieAccepted: state => state.GeneralModule.CookieAccepted
        }),
        categories: {
            get () {
                return this.$store.getters.getCategories;
            }
        },
        Famillies: {
            get () {
                return this.$store.getters.getFamilles;
            }
        },
        productCategoryListToShow () {
            let productCategoryListToShow = [];

            productCategoryListToShow =
                this.Famillies.filter(x => x.id !== 0 && x.id !== 3);

            return productCategoryListToShow;
        }
    },
    beforeMount () {
        this.$store.dispatch('INITIALIZE_ENSEIGNE_BY_FAMMILLE_ID', -1);

        if (this.$store.getters.getPanierGeneralClient) {
            if (this.$session.exists('panier')) {
                this.$session.remove('panier');
            }

            if (this.CookieAccepted) {
                this.$session.set('panier', this.$store.getters.getPanierGeneralClient);
            }
        } else {
            if (this.$session.get('panier')) {
                this.$store.commit('SET_GENERAL_PANIER_CLIENT', this.$session.get('panier'));
                this.$store.dispatch('GET_GENERAL_INFORMATIONS');
                this.$session.remove('panier');
            }

            this.$store.commit('RESET_PANIER');
        }

        this.$store.dispatch('INITIALIZE_LIST_FAMILLE');
    },
    beforeUpdate () {
        if (this.$session.exists('panier')) {
            this.$session.remove('panier');
        }

        if (this.CookieAccepted) {
            this.$session.set('panier', this.$store.getters.getPanierGeneralClient);
        }
    },
    methods: {
        searchShopFamille (famille) {
            this.$store.commit('SET_SELECT_FAMILLE', famille.id);

            this.$router.push({
                name: 'family',
                params: {
                    name_famille: famille.nom
                }
            });
        },
        doesFamillyHasShops (familly) {
            const doesFamillyHasShops = this.Shop && this.Shop[familly.id] && this.Shop[familly.id].shops &&
                !Array.isArray(this.Shop[familly.id].shops);

            return doesFamillyHasShops;
        }
    }
};
</script>

<style lang="scss" scoped>
.product-category-list-container {
    margin: 0 auto 60px auto;
    padding-right: 15px;
    padding-left: 15px;
    width: 95%;
    max-width: 1200px;
}
</style>
