import Connection from '@/pages/Connection';
import Subscription from '@/pages/Subscription';

import Home from '@/pages/client/Home';
import FamilyList from '@/pages/client/FamilyList';
import Family from '@/pages/client/Family';
import ClientProfile from '@/pages/client/Profile';
import Cart from '@/pages/client/Cart';
import ClientFAQ from '@/pages/client/FAQ';
import TermsOfService from '@/pages/client/TermsOfService';
import PrivacyPolicy from '@/pages/client/PrivacyPolicy';
import LegalNotice from '@/pages/client/LegalNotice';
import Shop from '@/pages/client/Shop';

import AislesManagement from '@/pages/vendor/AislesManagement';
import VendorProducts from '@/pages/vendor/VendorProducts';
import OrdersManagement from '@/pages/vendor/OrdersManagement';
import VendorProfile from '@/pages/vendor/Profile';
import VendorFAQ from '@/pages/vendor/FAQ';

import ShopList from '@/pages/admin/ShopList';
import VendorRegistration from '@/pages/admin/VendorRegistration';
import ShopDetails from '@/pages/admin/ShopDetails';
import ModifyProduct from '@/pages/admin/ModifyProduct';
import ProductList from '@/pages/admin/ProductList';

const routes = [
    { name: 'loginPage', path: '/login', component: Connection },
    { name: 'registrationPage', path: '/inscription', component: Subscription },

    { name: 'home', path: '/', component: Home },
    { name: 'familliesList', path: '/categories', component: FamilyList },
    { name: 'family', path: '/categories/:name_famille', component: Family },
    { name: 'profile', path: '/profil', component: ClientProfile },
    { name: 'cartRecap', path: '/commandes', component: Cart },
    { name: 'clientFaq', path: '/Faq', component: ClientFAQ },
    { name: 'cgv', path: '/conditions-generales-de-ventes', component: TermsOfService },
    { name: 'politique', path: '/politique-de-confidentialite', component: PrivacyPolicy },
    { name: 'mentions', path: '/mentions-legales', component: LegalNotice },
    { name: 'shop', path: '/shops/:city/:shopname', component: Shop },

    { name: 'vendorProduct', path: '/vendeur/produits', component: VendorProducts, meta: { title: 'Produits' } },
    { name: 'vendorProfile', path: '/vendeur/profil', component: VendorProfile, meta: { title: 'Mon compte' } },
    { name: 'vendorSections', path: '/vendeur/rayons', component: AislesManagement, meta: { title: 'Rayons' } },
    { name: 'vendorOrders', path: '/vendeur/commandes', component: OrdersManagement, meta: { title: 'Commandes' } },
    { name: 'vendorFaq', path: '/vendeur/Faq', component: VendorFAQ, meta: { title: 'FAQ' } },

    { name: 'ShopListBody', path: '/Admin/shops', component: ShopList, meta: { title: 'Partenaires' } },
    { name: 'AdminVendorRegistration', path: '/Admin/shops/registration', component: VendorRegistration, meta: { title: 'Partenaires' } },
    { name: 'AdminShopsDetails', path: '/Admin/shops/:shopId', component: ShopDetails, meta: { title: 'Partenaires' } },
    { name: 'AdminProductModify', path: '/Admin/product/modify/:produtId', component: ModifyProduct, meta: { title: 'Produits' } },
    { name: 'AdminProduct', path: '/Admin/product', component: ProductList, meta: { title: 'Produits' } },

    { name: 'BadUrl', path: '*', redirect: '/' }
];

export default routes;
