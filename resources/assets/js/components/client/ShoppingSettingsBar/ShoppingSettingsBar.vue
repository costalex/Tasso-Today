<template>
    <div
        id="NavBarComponent"
        class="shopping-settings-bar"
    >
        <div
            v-if="$route.name === 'familliesList'"
            id="NavBarsub"
            class="navbar-client"
        >
            <div class="around-me">
                <InputAddressAroundMe :selected-address="Address_select" />
            </div>

            <div class="delivery-pickup">
                <DateTimeDeliveryPickUp
                    :selected-date="date_select"
                    :config="config"
                    :delivery-time-end="date_livraison_end "
                    @date-picker-change="ondateChange"
                />
            </div>
        </div>

        <div
            v-if="$route.name === 'family'"
            id="NavBarFamilli"
            class="col-xs-12 famille-page-back-category-around-me-delivery-pickup-container"
        >
            <div class="col-sm-6 col-xs-12 no-padding back-category-nav">
                <div class="back-nav">
                    <button
                        class="btn back-to-families"
                        @click="BacktoLandingPage"
                    >
                        <i
                            aria-hidden="true"
                            class="fa fa-chevron-left"
                        />
                        Retour
                    </button>
                </div>

                <div class="category-list">
                    <select
                        v-model="select_famille"
                        class="filter-picker"
                    >
                        <option
                            v-for="option in Familles"
                            v-if="option.id !== 3 && option.id !== 0"
                            :key="option.id"
                            :value="option"
                        >
                            {{ option.nom }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="col-sm-6 col-xs-12 no-padding navbar-client">
                <div class="around-me">
                    <InputAddressAroundMe :selected-address="Address_select" />
                </div>

                <div class="delivery-pickup">
                    <DateTimeDeliveryPickUp
                        :selected-date="date_select"
                        :config="config"
                        :delivery-time-end="date_livraison_end "
                        @date-picker-change="ondateChange"
                    />
                </div>
            </div>
        </div>

        <div
            v-if="$route.name === 'shop'"
            id="NavBarShop"
            class="boutique-navbar"
        >
            <div class="col-sm-8 col-xs-12 navbar-client">
                <div class="around-me">
                    <InputAddressAroundMe :selected-address="Address_select" />
                </div>

                <div class="delivery-pickup">
                    <DateTimeDeliveryPickUp
                        :selected-date="date_select"
                        :config="config"
                        :delivery-time-end="date_livraison_end "
                        @date-picker-change="ondateChange"
                    />
                </div>
            </div>

            <div class="col-sm-4 col-xs-12 back-category-nav">
                <div class="open-cart">
                    <button
                        class="btn basket-btn"
                        @click="openpanier"
                    >
                        <img src="/storage/bobby_images/marketplace-client/icon-cart.svg">

                        <p>Panier magasin :</p>

                        <span class="total-products">
                            {{ total_product }}
                        </span>
                    </button>
                </div>

                <div
                    v-click-outside="closepanier"
                    v-if="openPanier"
                    class="cart"
                >
                    <div
                        id="boutique-name"
                        class="shop-cart-header"
                    >
                        <div class="shop-cart-name">
                            <h1>{{ shopname }}</h1>
                        </div>

                        <div class="shop-cart-delete">
                            <button @click="deletePanier">
                                <img src="/storage/bobby_images/marketplace-client/icon-garbage.svg">
                            </button>
                        </div>
                    </div>

                    <div
                        v-for="product in PanierEnseigne"
                        :key="product.id"
                        @change="setPanierEnseigneClient(PanierEnseigne)"
                    >
                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding product-line">
                            <div
                                class="col-md-2 col-sm-2 col-xs-2 no-padding"
                                style="padding-left: 5px;"
                            >
                                <img :src="product.img[0].image_miniature[0]">
                            </div>

                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                                    <p id="product-name">
                                        {{ product.nom }}
                                    </p>
                                </div>

                                <div class="col-xs-12 no-padding">
                                    <p id="model-product">
                                        {{ product.stocks.model }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="col-md-4 col-sm-4 col-xs-4 no-padding"
                                style="margin-top: 2%;"
                            >
                                <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                    <button
                                        class="btn btn-circle-sm badge sub-product"
                                        @click="sub_quantite(product)"
                                    >
                                        <i
                                            class="fa fa-minus"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>

                                <div
                                    id="product-quantity"
                                    class="col-md-4 col-sm-4 col-xs-4 no-padding"
                                >
                                    <p>
                                        {{ product.quantite }}
                                    </p>
                                </div>

                                <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                                    <button
                                        class="btn btn-circle-sm badge add-product"
                                        @click="add_quantite(product)"
                                    >
                                        <i
                                            class="fa fa-plus"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                                <div
                                    class="col-md-8 col-sm-8 col-xs-8 no-padding"
                                    style="margin-top: 15%;"
                                >
                                    <p>
                                        {{ (product.quantite * product.stocks.prix).toFixed(2).replace('.', ',') }}€
                                    </p>
                                </div>

                                <div
                                    class="col-md-4 col-sm-4 col-xs-4 no-padding"
                                    style="margin-top: 8px;"
                                >
                                    <button
                                        class="btn delete-product"
                                        @click="delete_product(product)"
                                    >
                                        <i
                                            class="fa fa-times-circle fa-lg"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="shop-cart-order-button btn-block">
                        <button
                            v-if="TypeAccount === 'Client'"
                            class="btn order-btn"
                            @click="gotoRecapPanier"
                        >
                            <div>
                                <span class="total-products">
                                    {{ total_product }}
                                </span>
                            </div>

                            <div>
                                <p>Commander</p>
                            </div>

                            <div>
                                {{ (total).toFixed(2).replace('.', ',') }}€
                            </div>
                        </button>

                        <a
                            v-else
                            href="/login"
                        >
                            <button
                                class="btn order-btn"
                                @click="save_panier"
                            >
                                <div
                                    class="col-md-2 col-sm-2 col-xs-2 no-padding"
                                    align="left"
                                >
                                    <span class="total-products">
                                        {{ total_product }}
                                    </span>
                                </div>

                                <div
                                    class="col-md-6 col-sm-6 col-xs-6 no-padding"
                                    align="center"
                                >
                                    Commander
                                </div>

                                <div
                                    class="col-md-4 col-sm-4 col-xs-4 no-padding"
                                    align="right"
                                >
                                    {{ (total).toFixed(2).replace('.', ',') }}€
                                </div>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div
            v-if="$route.name === 'cartRecap'"
            id="NavBarRecapPanier"
            class="col-xs-12 back-category-around-me-delivery-pickup-container"
        >
            <div class="col-sm-6 col-xs-12 no-padding back-category-nav">
                <div class="back-nav">
                    <button
                        class="btn back-to-families"
                        @click="BacktoLandingPage"
                    >
                        <i
                            aria-hidden="true"
                            class="fa fa-chevron-left"
                        />
                        Retour
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import InputAddressAroundMe from './InputAddressAroundMe';
import DateTimeDeliveryPickUp from './DateTimeDeliveryPickUp';

export default {
    name: 'NavBar',
    components: { InputAddressAroundMe, DateTimeDeliveryPickUp },
    directives: {
        'click-outside': {
            bind (el, binding, vNode) {
                if (typeof binding.value !== 'function') {
                    const compName = vNode.context.name;
                    let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`;
                    if (compName) {
                        warn += `Found in component '${compName}'`;
                    }

                    console.warn(warn);
                }

                const bubble = binding.modifiers.bubble;
                const handler = (e) => {
                    if (bubble || (!el.contains(e.target) && el !== e.target)) {
                        binding.value(e);
                    }
                };
                el.__vueClickOutside__ = handler;

                document.addEventListener('click', handler);
            },
            unbind (el) {
                document.removeEventListener('click', el.__vueClickOutside__);
                el.__vueClickOutside__ = null;
            }
        }
    },
    data: () => {
        return {
            date_select: new Date(),
            config: {},
            showModal: false,
            showModalLogn: false,
            openPanier: false,
            select_famille: {
                id: 0,
                nom: 'Familles'
            },
            minDate: new Date(),
            navBarShop: null,
            navBarShopOffsetTop: 0
        };
    },
    computed: {
        ...mapState({
            total: state => state.CartModule.panier_enseigne.total,
            total_product: state => state.CartModule.panier_enseigne.quantite_total,
            PanierEnseigne: state => state.CartModule.panier_enseigne.product,
            General_panier: state => state.CartModule.panier_general,
            CookieAccepted: state => state.GeneralModule.CookieAccepted
        }),
        date_livraison_end: {
            get () {
                const date = new Date(this.$store.getters.getEndDateLivraison);

                if (date.getMinutes() >= 10) {
                    return date.getHours() + ':' + date.getMinutes();
                } else {
                    return date.getHours() + ':' + date.getMinutes() + '0';
                }
            }
        },
        ShopInfo: {
            get () {
                return this.$store.getters.getShopInfo;
            }
        },
        shopname: {
            get () {
                return this.$store.getters.getShopName;
            }
        },
        Address_select: {
            get () {
                return this.$store.getters.getAddress;
            }
        },
        Familles: {
            get () {
                return this.$store.getters.getFamilles;
            }
        },
        TypeAccount: {
            get () {
                return this.$store.getters.getTypeUser;
            }
        }
    },
    watch: {
        select_famille: function (value) {
            if (this.$route.name === 'family' &&
                this.select_famille.nom !== this.$route.params.name_famille
            ) {
                this.$store.dispatch('INITIALIZE_ENSEIGNE_BY_FAMMILLE_ID', value.id);
                this.$store.commit('SET_SELECT_FAMILLE', value.id);
                this.$store.commit('SET_SELECT_CATEGORIE', 0);
                this.$store.dispatch('INITIALIZE_LIST_CATEGORIE', value.id);

                this.$router.push({
                    name: 'family',
                    params: {
                        name_famille: value.nom
                    }
                });
            }
        }
    },
    beforeUpdate () {
        let famille = this.$route.params.name_famille;
        let Families = this.$store.getters.getFamilles;
        for (let i = 0; i < Families.length; i++) {
            if (Families[i].nom === famille && this.select_famille.nom !== famille) {
                this.$store.dispatch('INITIALIZE_ENSEIGNE_BY_FAMMILLE_ID', Families[i].id);
                this.$store.commit('SET_SELECT_FAMILLE', Families[i].id);
                this.$store.commit('SET_SELECT_CATEGORIE', 0);
                this.$store.dispatch('INITIALIZE_LIST_CATEGORIE', Families[i].id);
                this.select_famille = Families[i];
            }
        }
    },
    beforeMount () {
        const FamilliesList = this.$store.getters.getFamilles;
        for (let index in FamilliesList) {
            if (this.$store.getters.getSelectFamille === FamilliesList[index].id) {
                this.select_famille = FamilliesList[index];

                if (this.$route.name === 'family') {
                    this.$store.dispatch('INITIALIZE_LIST_CATEGORIE', FamilliesList[index].id);
                }
            }
        }

        if (!this.$store.getters.getDateLivraison) {
            this.$store.commit('SET_DATE_LIVRAISON', new Date());
        } else {
            this.date_select = new Date(this.$store.getters.getDateLivraison);
        }

        let max = new Date();
        if ((new Date().getHours() + 3) > 18) {
            const min = new Date();
            min.setDate(min.getDate() + 1);
            min.setHours(9);
            min.setMinutes(0);
            min.setSeconds(0);
            min.setMilliseconds(0);
            min.toISOString();
            max = new Date(min.getFullYear(), min.getMonth(), min.getDate() + 7);
            max.setHours(18);

            this.config = {
                locale: 'fr',
                format: 'DD/MM/YYYY HH:mm',
                useCurrent: false,
                showClear: false,
                showClose: true,
                minDate: min,
                maxDate: max,
                daysOfWeekDisabled: [0],
                viewMode: 'days',
                enabledHours: ['9', '10', '11', '12', '13', '14', '15', '16', '17', '18'],
                stepping: 30
            };

            this.minDate = min;
            this.$store.commit('SET_DATE_LIVRAISON', min);
        } else {
            const newDate = new Date();
            newDate.setHours(new Date().getHours() + 3);
            newDate.setMinutes(0);
            newDate.setSeconds(0);
            newDate.setMilliseconds(0);
            newDate.toISOString();
            max = new Date(newDate.getFullYear(), newDate.getMonth(), newDate.getDate() + 7);
            max.setHours(18);

            this.config = {
                locale: 'fr',
                format: 'DD/MM/YYYY HH:mm',
                useCurrent: false,
                showClear: false,
                showClose: true,
                minDate: newDate,
                maxDate: max,
                daysOfWeekDisabled: [0],
                viewMode: 'days',
                enabledHours: ['9', '10', '11', '12', '13', '14', '15', '16', '17', '18'],
                stepping: 30
            };

            this.minDate = newDate;
            this.$store.commit('SET_DATE_LIVRAISON', newDate);
        }
    },
    mounted () {
        if (this.$route.name === 'shop') {
            this.navBarShop = document.getElementById('NavBarShop');
            this.navBarShopOffsetTop = this.navBarShop.offsetTop;
        }
    },
    created () {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed () {
        window.removeEventListener('scroll', this.handleScroll);
    },
    updated () {
        if (this.$route.name === 'shop') {
            this.navBarShop = document.getElementById('NavBarShop');
            this.navBarShopOffsetTop = this.navBarShop.offsetTop;
        }
    },
    methods: {
        handleScroll () {
            if (this.navBarShop !== null && window.pageYOffset > this.navBarShopOffsetTop) {
                this.navBarShop.classList.add('sticky');
            } else if (this.navBarShop !== null) {
                this.navBarShop.classList.remove('sticky');
            }
        },
        ondateChange (e) {
            if (e.date._d) {
                if (e.date._d.getHours() === 0) {
                    e.date._d.setHours(9);
                }

                this.date_select = e.date._d;
                this.$store.commit('SET_DATE_LIVRAISON', e.date._d);
            } else {
                this.$store.commit('SET_DATE_LIVRAISON', this.minDate);
            }
        },
        deletePanier () {
            this.$store.commit('RESET_PANIER');
            this.$store.commit('RESET_PRODUCT_CLIENT');
            this.$store.commit('ADD_TO_GENERAL_PANIER');
            this.$store.dispatch('GET_GENERAL_INFORMATIONS');
        },
        delete_product (product) {
            this.$store.commit('DELETE_PRODUCT_PANIER', product);
            this.$store.commit('ADD_TO_GENERAL_PANIER');
            this.$store.dispatch('GET_GENERAL_INFORMATIONS');
            this.$store.commit('RESET_PRODUCT_CLIENT');
            this.$store.commit('ADD_TO_GENERAL_PANIER');
            this.$store.dispatch('GET_GENERAL_INFORMATIONS');
        },
        add_quantite (product) {
            if (product.quantite < 100) {
                this.$store.commit('INCREMENT_PRODUCT_PANIER', product);
            }
        },
        sub_quantite (product) {
            if (product.quantite > 1) {
                this.$store.commit('DECREMENT_PRODUCT_PANIER', product);
            }
        },
        closepanier () {
            this.openPanier = false;
        },
        openpanier () {
            this.openPanier = !this.openPanier;
        },
        BacktoLandingPage () {
            this.save_panier();
            this.$store.commit('SET_SELECT_FAMILLE', 0);
            this.$router.push({ name: 'familliesList' });
        },
        save_panier () {
            if (this.CookieAccepted) {
                this.$session.set('panier', this.General_panier);
            }
        },
        gotoRecapPanier () {
            this.save_panier();
            this.$router.push({ name: 'cartRecap' });
        },
        setPanierEnseigneClient (PanierEnseigne) {
            this.$store.commit('SET_PANIER_ENSEIGNE_CLIENT', PanierEnseigne);
            this.$store.commit('ADD_TO_GENERAL_PANIER');
            this.$store.dispatch('GET_GENERAL_INFORMATIONS');
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.shopping-settings-bar {
    height: 70px;
    border-bottom: 1px solid $grey;
    padding: 0;
    margin: 0 auto;
    text-align: left;

    .delivery-pickup,
    .around-me {
        width: 50%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    @media only screen and (min-width: 768px) {
        height: 100px;

        .famille-page-back-category-around-me-delivery-pickup-container {
            flex-direction: row;

            .back-category-nav {
                width: auto;
            }
        }
    }

    @media only screen and (max-width: 395px) {
        .famille-page-back-category-around-me-delivery-pickup-container {
            padding: 0 5px;
        }
    }

    .famille-page-back-category-around-me-delivery-pickup-container {
        height: 100%;
        position: relative;
        align-items: center;
        max-width: 1200px;
        flex-direction: column-reverse;

        .back-category-nav {
            margin: 0;
            height: 100%;
            max-width: 1200px;
            display: flex;
            align-items: center;

            .back-nav {
                width: auto;

                .back-to-families {
                    border: none;
                    background-color: #ff88ee00;
                    text-decoration: none;

                    .fa-chevron-left {
                        position: relative;
                        top: 1px;
                    }
                }
            }

            .category-list {
                width: 100%;
                display: flex;
                justify-content: flex-end;

                .filter-picker {
                    @include prefix(box-shadow, none, webkit moz ms o);
                    @include prefix(border-radius, 5px, webkit moz ms o);
                    display: inline-block;
                    margin-right: 10px;
                    overflow: hidden;
                    padding: 5px 8px;
                    width: -moz-available;
                    width: -webkit-fill-available;
                    width: fill-available;
                    background-image: url(/storage/bobby_images/marketplace-client/icon-dropdown.svg);
                    background-repeat: no-repeat;
                    background-position: right center;
                    background-color: #ff88ee00;
                    border: none;
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    cursor: pointer;
                }
            }

            .categories-list .filter-picker {
                width: 90%;
            }
        }

        .navbar-client {
            margin: 0 auto;
            height: 100%;
            max-width: 1200px;
            display: flex;

            p {
                @include font-size(1.4);
                margin: 0 auto;
            }

            i {
                color: $primary-color;
            }

            .open-cart {
                margin-bottom: 5px;
            }
        }
    }

    #NavBarShop {
        background-color: white;
        margin: auto;

        &.sticky {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            left: 50%;
            transform: translateX(-50%);

            .navbar-client {
                @media only screen and (max-width: 767px) {
                    display: none;
                }
            }
        }
    }

    .boutique-navbar {
        max-width: 1200px;
        margin: 0 auto;
        height: 70px;
        border-bottom: 1px solid #d8d8d8;
        padding: 0;
        text-align: left;

        .back-category-nav {
            margin: 0;
            height: 100%;
            max-width: 1200px;
            display: flex;
            position: relative;
            align-items: center;

            .back-nav {
                width: auto;

                .back-to-families {
                    border: none;
                    background-color: #ff88ee00;
                    text-decoration: none;

                    &:hover {
                        color: $primary-color;
                        font-weight: 500;
                        cursor: pointer;
                    }

                    .fa-chevron-left {
                        position: relative;
                        top: 1px;
                    }
                }
            }

            .open-cart {
                display: flex;
                position: relative;
                justify-content: flex-end;
                width: 100%;

                .basket-btn {
                    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
                    @include prefix(border-radius, 20px, webkit moz ms o);
                    color: $white;
                    height: 45px;
                    display: flex;
                    position: relative;
                    align-items: center;
                    justify-content: space-evenly;
                    border-radius: 4px;
                    background-color: #DD291C;

                    &:hover {
                        @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                    }

                    img {
                        height: 23px;
                        margin-right: 10px;
                    }

                    p {
                        margin: 0;
                        color: $white;
                    }

                    .total-products {
                        margin-left: 10px;
                        min-width: 22px;
                    }
                }

                @media only screen and (max-width: 767px) {
                    .basket-btn {
                        width: 100%;
                        justify-content: space-between;
                    }
                }
            }

            .cart {
                background-color: $white;
                position:absolute;
                top: 100%;
                width: 100%;
                left: 0;
                @include prefix(box-shadow, $shadow-box, webkit moz ms o);
                border: 1px solid $grey;
                border-radius: 4px;
                z-index: 100;

                .shop-cart-header {
                    height: 40px;
                    display: flex;
                    position: relative;
                    align-items: center;
                    padding: 0 10px;

                    .shop-cart-name {
                        width: 100%;

                        h1 {
                            margin: 0;
                        }
                    }

                    .shop-cart-delete {
                        display: flex;
                        position: relative;
                        justify-content: flex-end;
                        width: 10%;

                        button {
                            padding: 0 0 0 10px;
                        }
                    }
                }

                .shop-cart-order-button {
                    padding: 0 10px;

                    a {
                        text-decoration: none;
                    }
                }

                #boutique-name {
                    background-color: $light-grey;
                    border-bottom: 1px solid $grey;

                    h1 {
                        @include font-size(1.4);
                        margin: 10px 0 0 10px;
                        font-weight: 500;
                    }

                    button {
                        background-color: $light-grey;
                        border: none;
                        border-left: 1px solid $grey;
                        padding-right: 0;
                    }

                    img {
                        margin: 5px 0 5px 0;

                        &:hover {
                            content: url('/storage/bobby_images/marketplace-client/icon-garbage-hover.svg');
                        }
                    }
                }

                #product-name {
                    font-weight: 500;
                    margin-bottom: 5px;
                    padding-left: 10px
                }

                #model-product {
                    color: $dark-grey;
                    padding-left: 10px;
                }

                #product-quantity {
                    padding: 3px 0 0 7px;

                    p {
                        font-weight: 500;
                    }
                }

                .product-line {
                    margin-top: 15px;

                    img {
                        border: 1px solid $grey;
                    }

                    p {
                        @include font-size(1.2);
                        padding: 0;
                    }

                    .add-product, .sub-product {
                        @include font-size(1.2);
                        @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
                        background-color: $light-grey;

                        i {
                            color: $pastel-green;
                            font-weight: 500;
                        }

                        &:hover {
                            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                        }
                    }

                    .delete-product {
                        background-color: transparent;
                        border: none;
                        color: $dark-grey;
                        padding: 0;

                        &:hover {
                            color: $red;
                        }
                    }
                }

                .btn-block {
                    .order-btn {
                        display: flex;
                        position: relative;
                        justify-content: space-between;
                        align-items: center;
                        @include prefix(border-radius, 4px, webkit moz ms o);
                        @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
                        background-color: #DD291C;
                        color: #FFF;
                        font-style: normal;
                        font-weight: 400;
                        font-size: 15px;
                        margin: 10px 0 10px 0;
                        width: -moz-available;
                        width: -webkit-fill-available;
                        width: fill-available;

                        &:hover {
                            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                        }

                        p {
                            margin: 0;
                            color: #FFF;
                            text-transform: uppercase;
                            font-weight: 500;
                        }
                    }
                }
            }

            @media only screen and (max-width: 767px) {
                .cart {
                    border-radius: 0;
                    border-left: none;
                    border-right: none;
                }
            }
        }
    }

    .navbar-client {
        margin: 0 auto;
        height: 100%;
        max-width: 1200px;
        display: flex;
    }

}
</style>
