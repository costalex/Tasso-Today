<template>
    <div id="FamilliePage" >
        <NavBar class="famille-page-shopping-settings-bar" />

        <div v-show="loadingListEnseignes === loadingStates.LOADED">
            <div class="sub-category-filter-container">
                <ButtonFilter
                    v-for="Categorie in productCategoriesToShow"
                    :key="Categorie.id"
                    :filter-name="Categorie.nom"
                    class="btn-filter"
                    @click="scrollToSubCategory(Categorie.id)"
                />
            </div>

            <div>
                <ProductCategoryShopList
                    v-for="Categorie in productCategoriesToShow"
                    :id="`product_category_${Categorie.id}_shop_list`"
                    :key="Categorie.id"
                    :title="Categorie.nom"
                    :shops="Object.values(Shop[Categorie.id].shops)"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex';
import NavBar from '@/components/client/ShoppingSettingsBar/ShoppingSettingsBar';
import ProductCategoryShopList from '@/components/client/ProductCategoryShopList';
import ButtonFilter from '@/components/client/PageFamily/ButtonFilter';

export default {
    name: 'Family',
    components: {
        NavBar,
        ProductCategoryShopList,
        ButtonFilter
    },
    computed: {
        ...mapState({
            Categories: state => state.SelectTabModule.Categories,
            Shop: state => state.ShopModule.list_enseignes
        }),
        ...mapGetters([
            'loadingStates',
            'loadingListEnseignes'
        ]),
        ...mapGetters({
            selectedFamillyId: 'getSelectFamille'
        }),
        productCategoriesToShow () {
            let productCategoriesToShow = [];

            productCategoriesToShow =
                this.Categories.filter(x =>
                    x.id !== 0 &&
                        this.Shop[x.id] &&
                            Object.values(this.Shop[x.id].shops).length > 0
                );

            return productCategoriesToShow;
        }
    },
    mounted () {
        this.$store.dispatch('INITIALIZE_ENSEIGNE_BY_FAMMILLE_ID', this.selectedFamillyId);
    },
    beforeDestroy () {
        this.$store.commit('SET_LOADIND_LIST_ENSEIGNES', null);
    },
    methods: {
        scrollToSubCategory (subCategoryId) {
            const subCategoryElement =
                document.getElementById(`product_category_${subCategoryId}_shop_list`).firstChild;

            if (subCategoryElement) {
                this.$scrollTo(subCategoryElement, 500);
            }

            this.$store.commit('SET_SELECT_CATEGORIE', subCategoryId);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_mixins';

#FamilliePage {
    .famille-page-shopping-settings-bar {
        min-height: 70px;
        border-bottom: 1px solid $grey;
        padding: 0;
        margin:0 auto;
        text-align: left;
        display: flex;
        position: relative;
        justify-content: center;
    }

    .sub-category-filter-container {
        text-align: center;
        margin-top: 20px;
        margin-bottom: 50px;

        .btn-filter {
            margin: 10px;
            @include font-size(1.5);
        }

        @media only screen and (max-width: 600px) {
            margin-top: 60px;
        }
    }
}
</style>
