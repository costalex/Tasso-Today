<template>
    <div
        id="BoddyEnseingeDetails"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
    >
        <h1 id="Shop-name">
            {{ Shop_name }}
        </h1>

        <div
            id="HeadShopDetails"
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
        >
            <ul class="nav nav-tabs">
                <li
                    id="Informations"
                    :class="{'blue-menu-selected': tabName === 'Informations' }"
                    @click="tabName = 'Informations'"
                >
                    Informations
                </li>

                <li
                    id="Produits"
                    :class="{'blue-menu-selected': tabName === 'Produits' }"
                    @click="tabName = 'Produits'"
                >
                    Produits
                </li>

                <li
                    id="Ajouter_produits"
                    :class="{'blue-menu-selected': tabName === 'Ajouter_produits' }"
                    @click="tabName = 'Ajouter_produits'"
                >
                    Ajouter des produits
                </li>
            </ul>
        </div>

        <div
            v-if="tabName === 'Informations'"
            id="CoreEnseingeInformations"
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
        >
            <InformationShop/>
        </div>

        <div
            v-if="tabName === 'Produits'"
            id="CoreEnseingeProduits"
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
        >
            <ProduitsShop/>
        </div>

        <div
            v-if="tabName === 'Ajouter_produits'"
            id="CoreEnseingeAjouterProduits"
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
        >
            <AddProduitsShop/>
        </div>
    </div>
</template>

<script>
import InformationShop from '@/components/admin/PageShopDetails/Informations/ShopInfoBody';
import ProduitsShop from '@/components/admin/PageShopDetails/Products/ShopProductBody';
import AddProduitsShop from '@/components/admin/PageShopDetails/Add/ShopAddProductBody';

export default {
    name: 'ShopDetails',
    components: {
        InformationShop,
        ProduitsShop,
        AddProduitsShop
    },
    data: () => {
        return {
            tabName: 'Informations'
        };
    },
    computed: {
        Shop_name: {
            get () {
                return this.$store.getters.getEnseingeName;
            }
        }
    },
    mounted: function () {
        this.$store.dispatch('SET_ENSEIGNE_DETAILS');
        this.$store.dispatch('INITIALIZE_LIST_ENTREPRISE_TYPE');
    }
};
</script>

<style lang="scss" scoped>
#HeadShopDetails {
    margin-top: 10px;
    ul {
        li {
            cursor: pointer;
            margin-left: 10px;
            margin-right: 10px;
        }
    }
}
</style>
