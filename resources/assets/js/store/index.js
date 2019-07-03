import Vue from 'vue';
import Vuex from 'vuex';

import NotificationsModule from './modules/Notifications';
import GeneralModule from './modules/General';
import SelectTabModule from './modules/SelectTab';
import ProductModule from './modules/Produits/Produits';
import NewProductDetailsModule from './modules/Produits/NewProduitsDetails';
import ModifyProductVendorModule from './modules/Produits/ModifProduitVendeur';
import ShopModule from './modules/Enseigne/Enseigne';
import DetailsShopModule from './modules/Enseigne/DetailEnseigne';
import RaysModule from './modules/Rayons/GestionRayons';
import ShelfModule from './modules/Rayons/GestionEtagere';
import OrdersModule from './modules/Commandes/Commandes';
import ClientOrderModule from './modules/Commandes/CommandeClient';
import ClientStore from './modules/BoutiqueClient/ClientStore';
import CartModule from './modules/BoutiqueClient/Panier';

import createPersistedState from 'vuex-persistedstate';

Vue.use(Vuex);

Vue.config.productionTip = true;
Vue.config.silent = true;

const store = new Vuex.Store({
    modules: {
        NotificationsModule: NotificationsModule,
        GeneralModule: GeneralModule,
        SelectTabModule: SelectTabModule,
        ProductModule: ProductModule,
        NewProductDetails: NewProductDetailsModule,
        ModifyProductVendorModule: ModifyProductVendorModule,
        ShopModule: ShopModule,
        DetailsShopModule: DetailsShopModule,
        RaysModule: RaysModule,
        ShelfModule: ShelfModule,
        OrdersModule: OrdersModule,
        ClientOrderModule: ClientOrderModule,
        ClientStore: ClientStore,
        CartModule: CartModule
    },
    plugins: [createPersistedState()]
});

export default store;
