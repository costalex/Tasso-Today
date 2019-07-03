<template>
    <div id="BoddyBoutique">
        <div
            :style="'background-image: url(' + ShopInfo.banniere + ');'"
            class="shop-cover"
        >
            <div class="container-fluid shop-details">
                <div class="row shop-details-container">
                    <div class="col-md-6 col-xs-12 shop-details-description">
                        <div class="shop-name-logo">
                            <img
                                :src="ShopInfo.path_file_logo_entreprise"
                                class="shop-logo"
                                alt="logo de la boutique"
                                title="shopLogo"
                            >

                            <p class="shop-name">
                                {{ ShopInfo.nom_enseigne }}
                            </p>
                        </div>

                        <p>{{ ShopInfo.description }}</p>
                    </div>

                    <div class="col-md-6 col-xs-12 shop-details-infos">
                        <div class="row shop-services">
                            <p class="col-xs-12 title">
                                Services de votre boutique :
                            </p>

                            <div class="col-md-6 col-xs-12 service-item">
                                <p>
                                    3,90€ / livraison
                                </p>
                            </div>

                            <div class="col-md-6 col-xs-12 service-item">
                                <p>
                                    Retrait en magasin
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="nav-bar-container">
            <NavBar/>
        </div>

        <div class="container-fluid shop-body-container">
            <div class="row">
                <div class="col-md-2 col-xs-12 shop-menu">
                    <MenuBoutique/>
                </div>

                <div class="col-md-10 col-xs-12 shop-shelves">
                    <EtagereRendu/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import NavBar from '@/components/client/ShoppingSettingsBar/ShoppingSettingsBar';
import MenuBoutique from '@/components/client/PageShop/ShopMenu';
import EtagereRendu from '@/components/client/PageShop/ShopShelves';

export default {
    name: 'Shop',
    components: {
        NavBar,
        MenuBoutique,
        EtagereRendu
    },
    computed: {
        ...mapState({
            GeneralPanier: state => state.CartModule.panier_general,
            CookieAccepted: state => state.GeneralModule.CookieAccepted
        }),
        TypeAccount: {
            get () {
                if (this.$store.getters.getTypeUser === 'Client') {
                    this.$store.dispatch('GET_LIST_CARD_PAYMENT');
                }

                return this.$store.getters.getTypeUser;
            }
        },
        ShopInfo: {
            get () {
                return this.$store.getters.getShopInfo;
            }
        }
    },
    beforeMount () {
        if (this.$route.params.city && this.$route.params.city !== 'api') {
            this.$store.commit('SET_SHOP_NAME', this.$route.params.shopname);
            this.$store.commit('SET_CITY_SHOP', this.$route.params.city);
            this.$store.dispatch('SET_ENSEIGNE_DETAILS_FOR_CLIENT');
        }

        this.$store.commit('SET_PANIER_ENSEIGNE');
    },
    beforeUpdate: function () {
        if (this.$session.exists('panier')) {
            this.$session.remove('panier');
        }

        if (this.CookieAccepted) {
            this.$session.set('panier', this.GeneralPanier);
        }
    },
    destroyed () {
        this.$store.commit('RESET_PANIER');
        this.$store.commit('SET_RESET_CLIENT_STORE');
    }
};
</script>

<style lang="scss" scoped>
.nav-bar-container {
    margin-bottom: 10px;
}

.shop-cover {
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center center;
    height: 300px;
    max-width: 1200px;
    margin: auto;
    padding: 0;
    position: relative;

    .shop-details {
        max-width: 1000px;
        width: 100%;
        margin: 0 auto;
        position: absolute;
        bottom: 0;
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);

        @media only screen and (max-width: 650px) {
            flex-direction: column;
        }

        .shop-details-container {
            height: 100%;
            min-height: 130px;
            width: 100%;
            background-color: #ffffff;
            border-radius: 4px 4px 0 0;
            margin: 0 auto;
            padding: 5px;
            display: flex;
            border-bottom: 1px solid #d8d8d8;

            .shop-details-description,
            .shop-details-infos {
                max-height: 130px;
                position: relative;
                align-items: center;

                .shop-services {
                    .title {
                        @media only screen and (max-width: 600px) {
                            display: none;
                        }
                    }

                    .service-item {
                        p {
                            min-height: 25px;
                            text-align: center;
                            margin-top: 3px;
                            border-radius: 45px / 45px;
                            background: #ffffff;
                            box-shadow: 0 1px 2px grey;
                        }

                        @media only screen and (max-width: 600px) {
                            padding-top: 7px;
                        }
                    }
                }
            }

            .shop-details-description {
                .shop-name-logo {
                    display: flex;
                    position: relative;
                    align-items: center;
                    margin-bottom: 12px;

                    .shop-logo {
                        width: 50px;
                        height: 50px;
                    }

                    .shop-name {
                        font-size: 22px;
                        margin: 0 0 0 12px;
                    }
                }
            }
        }
    }
}

.shop-body-container {
    max-width: 1200px;
    margin-top: 100px;

    @media only screen and (max-width: 600px) {
        .shop-shelves {
            padding: 0;
        }
    }
}
</style>
