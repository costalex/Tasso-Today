<template>
    <div
        id="RecapPanier"
        class="col-xs-12 recap-cart-page"
    >
        <NavBar class="nav-bar-no-border" />

        <div class="recap-cart-block col-sm-8 col-xs-12 no-padding">
            <p id="title-global">VOTRE PANIER</p>

            <div
                v-for="(panier, index) in GeneralPanier"
                :key="index"
            >
                <PanierShop
                    :panier="panier"
                    :modal-id="'Modal' + index"
                />
            </div>

            <div
                id="total_paniers"
                class="col-md-12 col-sm-12 col-xs-12 no-padding total-carts"
            >
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding sub-total-cart">
                    <div class="col-md-8 col-sm-8 col-xs-8 no-padding">
                        <h3>Sous-total panier</h3>
                    </div>

                    <div class="col-md-4 col-sm-4 col-xs-4 no-padding">
                        <div
                            class="col-md-5 col-sm-5 col-xs-5 no-padding"
                            style="padding-top: 15px;"
                        >
                            <p class="quantity">{{ GeneralQuantite }}</p>
                        </div>

                        <div
                            class="col-md-6 col-sm-6 col-xs-6 no-padding"
                            style="padding-top: 15px;"
                        >
                            <p
                                v-if="Livraison"
                                class="total-price"
                            >
                                {{ (Genral_information.total_montant_entreprises - (Genral_information.total_livraisons_entreprises * 2.90)).toFixed(2).replace('.', ',') }}€
                            </p>

                            <p
                                v-else
                                class="total-price"
                            >
                                {{ (Genral_information.total_montant_entreprises).toFixed(2).replace('.', ',') }}€
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12 no-padding delivery-infos">
                    <div class="col-xs-8 no-padding">
                        <p id="title">
                            Livraison
                        </p>
                    </div>

                    <div
                        class="col-xs-4 no-padding"
                        align="center"
                    >
                        <p
                            v-if="Livraison"
                            id="total-delivery"
                        >
                            {{ (Genral_information.total_livraisons_entreprises * 2.90).toFixed(2).replace('.', ',') }}€
                        </p>
                    </div>

                    <div
                        class="col-sm-8 col-sm-offset-2 col-xs-12 no-padding"
                        align="center"
                    >
                        <div class="col-xs-3 no-padding">
                            <p :class="{ '': !Livraison }">
                                Retrait
                            </p>
                        </div>

                        <div class="col-xs-2 no-padding">
                            <label class="switch">
                                <input
                                    v-model="Livraison"
                                    type="checkbox"
                                >

                                <span class="slider round" />
                            </label>
                        </div>

                        <div class="col-xs-3 no-padding">
                            <p :class="{'': Livraison }">
                                Livraison
                            </p>
                        </div>

                        <div
                            id="shop-delivery-price"
                            class="col-xs-3 no-padding"
                        >
                            <p style="font-weight: normal">
                                (2,90€/boutique)
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 no-padding total-cart">
                    <div class="col-xs-8 no-padding">
                        <h3>TOTAL PANIER </h3>
                    </div>

                    <div
                        class="col-xs-4 no-padding"
                        align="center"
                    >
                        <p>
                            {{ (Genral_information.total_montant_entreprises*1).toFixed(2).replace('.', ',') }}€
                        </p>
                    </div>
                </div>
            </div>

            <div
                id="valider_paniers"
                class="col-xs-12 no-padding order-block"
            >
                <div class="col-xs-6 no-padding cart-confirm-cgv">
                    <p>
                        En cliquant sur Commander, vous acceptez nos
                        <span>
                            <a
                                href="/conditions-generales-de-ventes"
                                target="_blank"
                            >
                                Conditions Générales de Vente et d'Utilisation
                            </a>
                        </span>
                        et notre
                        <span>
                            <a
                                href="/politique-de-confidentialite"
                                target="_blank"
                            >
                                Politique de confidentialité
                            </a>
                        </span>
                    </p>
                </div>

                <div class="col-xs-6 no-padding">
                    <div
                        v-if="!Aff_card"
                        class="loader"
                    />

                    <div v-else-if="GeneralPanier.length > 0">
                        <button
                            v-if="Livraison && Address !== ''"
                            class="btn btn-order"
                            type="button"
                            data-backdrop="static"
                            data-toggle="modal"
                            data-target="#PaymentModal"
                            @click="send_panier"
                        >
                            Commander
                        </button>

                        <button
                            v-else-if="!Livraison"
                            class="btn btn-order"
                            type="button"
                            data-backdrop="static"
                            data-toggle="modal"
                            data-target="#PaymentModal"
                            @click="send_panier"
                        >
                            Commander
                        </button>

                        <button
                            v-else
                            class="btn btn-order"
                            type="button"
                            @click="send_panier"
                        >
                            Commander
                        </button>
                    </div>

                    <button
                        v-else
                        class="btn btn-order"
                        type="button"
                    >
                        Commander
                    </button>
                </div>
            </div>
        </div>

        <div class="col-sm-3 col-xs-12 no-padding recap-delivery-infos">
            <div
                id="delivery-title"
                class="col-md-12 col-sm-12 col-xs-12 no-padding"
            >
                <h3>INFOS LIVRAISON</h3>
            </div>

            <div v-if="Livraison">
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                    <h2>Adresse de livraison</h2>
                </div>

                <p>Adresse <span>*</span></p>

                <vue-google-autocomplete
                    id="map"
                    ref="address"
                    :value="Address"
                    :enable-geolocation="true"
                    :geolocation-options="{ enableHighAccuracy: true, timeout: 5000, maximumAge: 0 }"
                    classname="form-control"
                    placeholder="Saisissez votre adresse de livraison"
                    country="fr"
                    @placechanged="getAddressData"
                />

                <p>Informations complémentaires</p>

                <input
                    v-model="Information_complementaire"
                    placeholder="Numéro de bâtiment, digicode..."
                >
            </div>

            <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                <h2>Informations personnelles</h2>
            </div>

            <div>
                <p>Prénom <span>*</span></p>
                <input
                    v-model="Prenom"
                    placeholder="Saisissez votre prénom"
                    required
                >

                <p>Nom <span>*</span></p>
                <input
                    v-model="Nom"
                    placeholder="Saisissez votre nom"
                    required
                >

                <p>Téléphone <span>*</span></p>
                <input
                    v-model="Telephone"
                    placeholder="Saisissez votre numéro de portable"
                    required
                >

                <p>E-mail <span>*</span></p>
                <input
                    v-model="Email"
                    placeholder="Saisissez votre e-mail"
                    required
                >
            </div>

            <h2>Information paiement</h2>

            <p>
                Carte bancaire
                <span>*</span>
            </p>

            <Stripecard/>
        </div>

        <PaymentModal
            v-show="showModal"
            id="PaymentModal"
            class="modal fade"
            role="dialog"
        />
    </div>
</template>

<script>
import { mapState } from 'vuex';
import VueGoogleAutocomplete from 'vue-google-autocomplete';
import Stripecard from '@/components/client/Stripe/StripeCard';
import NavBar from '@/components/client/ShoppingSettingsBar/ShoppingSettingsBar';
import PanierShop from '@/components/client/PageCart/CartRecapShop';
import PaymentModal from '@/components/client/PageCart/PaymentModal';
import NotificationTypes from '@/class/NotificationTypes';

export default {
    name: 'Cart',
    components: {
        VueGoogleAutocomplete,
        Stripecard,
        NavBar,
        PanierShop,
        PaymentModal
    },
    data: () => {
        return {
            showModal: false,
            notificationTypes: new NotificationTypes()
        };
    },
    computed: {
        ...mapState({
            Aff_card: state => state.ClientOrderModule.aff_card,
            verif_Cart_Pay: state => state.ClientOrderModule.pay_statu,
            Genral_information: state => state.ClientOrderModule.General_information,
            GeneralPanier: state => state.CartModule.panier_general,
            GeneralQuantite: state => state.CartModule.general_quantite
        }),
        ...mapState({
            Address_order: state => state.GeneralModule.Address,
            Prenom_order: state => state.ClientOrderModule.Prenom,
            Nom_order: state => state.ClientOrderModule.Nom,
            Telephone_order: state => state.ClientOrderModule.Telephone,
            Email_order: state => state.ClientOrderModule.Email
        }),
        Livraison: {
            set (value) {
                this.$store.commit('SET_MODE_LIVRAISON', value);
                this.$store.dispatch('GET_GENERAL_INFORMATIONS');
            },
            get () {
                return this.$store.getters.getLivraiosnMode;
            }
        },
        Address: {
            set (value) {
                this.$store.commit('SET_ADDRESS', value);
            },
            get () {
                return this.Address_order;
            }
        },
        Information_complementaire: {
            set (value) {
                this.$store.commit('SET_INFORMATION_COMPLEMENTAIRE', value);
            },
            get () {
                return this.$store.getters.getInformationcomplementaire;
            }
        },
        Prenom: {
            set (value) {
                this.$store.commit('SET_PRENOM', value);
            },
            get () {
                return this.Prenom_order;
            }
        },
        Nom: {
            set (value) {
                this.$store.commit('SET_NOM', value);
            },
            get () {
                return this.Nom_order;
            }
        },
        Telephone: {
            set (value) {
                this.$store.commit('SET_TELEPHONE', value);
            },
            get () {
                return this.Telephone_order;
            }
        },
        Email: {
            set (value) {
                this.$store.commit('SET_EMAIL', value);
            },
            get () {
                return this.Email_order;
            }
        }
    },
    beforeMount () {
        this.$store.dispatch('GET_GENERAL_INFORMATIONS');
        this.$store.commit('SET_ADDRESS', this.$store.getters.getAddressLivraison);
        this.$store.commit('SET_PRENOM', this.$store.getters.getprenom);
        this.$store.commit('SET_NOM', this.$store.getters.getnom);
        this.$store.commit('SET_EMAIL', this.$store.getters.getemail);
        this.$store.commit('SET_TELEPHONE', this.$store.getters.gettelephone);
    },
    methods: {
        send_panier () {
            if (this.$store.getters.getprenom !== '' &&
                this.$store.getters.getnom !== '' &&
                this.$store.getters.gettelephone !== '' &&
                this.$store.getters.getemail !== ''
            ) {
                if (this.$store.getters.getLivraiosnMode &&
                    this.$store.getters.getAddressLivraison !== ''
                ) {
                    this.showModal = true;
                    this.$store.dispatch('SEND_PANIER', this.GeneralPanier);
                } else if (!this.$store.getters.getLivraiosnMode) {
                    this.showModal = true;
                    this.$store.dispatch('SEND_PANIER', this.GeneralPanier);
                } else {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.ERROR,
                        message: 'Vous avez demandé la livraison pour cela vous devez renseigner une adresse'
                    });
                }
            } else {
                this.$store.dispatch('NOTIFY', {
                    type: this.notificationTypes.ERROR,
                    message: 'Vous devez remplir tous les champs comportant une étoile'
                });
            }
        },
        getAddressData (addressData, placeResultData) {
            this.$store.commit('SET_ADDRESS', placeResultData.formatted_address);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.recap-cart-page {
    background-color: $light-grey;

    @media (min-width: 768px) {
        padding-left: 100px;
    }

    .recap-cart-block {
        @include prefix(box-shadow, $shadow-box, moz webkit o ms);
        @include prefix(border-radius, 3px, webkit moz ms o);
        border: 1px solid $grey;
        margin-bottom: 100px;

        #title-global {
            @include font-size(1.8);
            background-color: $white;
            font-weight: 500;
            margin: 0;
            padding: 10px 0 10px 10px;
            border-top-right-radius: 2px;
            border-top-left-radius: 2px;
        }

        .total-carts {
            background-color: $light-grey;

            .sub-total-cart {
                border-bottom: 1px solid $grey;
                border-top: 1px solid $grey;
                padding-left: 10px;

                h3 {
                    font-weight: 500;
                }

                .quantity {
                    font-weight: 500;
                }

                .total-price {
                    font-weight: 500;
                }
            }

            .delivery-infos {
                border-bottom: 1px solid $grey;
                padding-top: 10px;

                p {
                    @include font-size(1.2);
                    font-weight: 500;
                }

                #title {
                    @include font-size(1.5);
                    padding-left: 10px;
                }

                #shop-delivery-price {
                    font-style: italic;
                }

                #total-delivery {
                    @include font-size(1.5);
                }
            }

            .total-cart {
                border-bottom: 1px solid $grey;
                padding-top: 10px;
                @include font-size(1.7);
                font-weight: 500;

                h3 {
                    @include font-size(1.5);
                    margin: 0;
                    padding-left: 10px;
                }
            }
        }

        .order-block {
            background-color:#FFF;
            padding: 15px 0 0 15px;
            border-bottom-left-radius: 2px;
            border-bottom-right-radius: 2px;

            p {
                @include font-size(1.3);
                font-weight: 400;
                font-family: "Rubik";
            }

            span {
                color: #000;
                font-weight: 400;

                a {
                    color: #331B99;
                    font-weight: 500;
                }
            }

            .btn-order {
                @include font-size(1.4);
                @include prefix(border-radius, 5px, webkit moz ms o);
                @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
                background-color: $pastel-green;
                color: $white;
                font-weight: 500;
                margin: 10px;
                width: -moz-available;
                width: -webkit-fill-available;
                width: fill-available;

                &:hover {
                    @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                }

                .total-products {
                    background-color: darken($pastel-green, 8%);
                    border: 1px inset $pastel-green;
                    border-radius: 5px;
                    padding: 0 10px 0 10px;
                }
            }
        }
    }

    .recap-delivery-infos {
        @include prefix(box-shadow, $shadow-box, moz webkit o ms);
        @include prefix(border-radius, 3px, webkit moz ms o);
        background-color: $white;
        border: 1px solid $grey;
        margin: 0 0 100px 30px;
        padding-bottom: 30px;

        @media (min-width: 0px) and (max-width: 767px) {
            margin: 0 0 100px 0;
        }

        p {
            @include font-size(1.4);
            font-weight: 500;
            margin: 0;
            padding: 5px 0 5px 5px;
        }

        h2 {
            background-color: $lighter-grey;
            border-bottom: 1px solid $grey;
            border-top: 1px solid $grey;
            font-weight: 500;
            margin: 0;
            padding: 10px;
        }

        span {
            color: $red;
            font-weight: 500;
        }

        input {
            @include font-size(1.3);
            @include prefix(border-radius, 5px, moz webkit o ms);
            @include placeholder {
                color: $dark-grey;
            }
            background-color: $light-grey;
            border: 1px solid $grey;
            color: $secondary-color;
            margin: 0 5px 10px 5px;
            padding: 8px;
            width: -moz-available;
            width: -webkit-fill-available;
            width: fill-available;
        }

        #delivery-title {
            h3 {
                @include font-size(1.8);
                font-weight: 500;
                margin: 0;
                padding: 10px;
            }
        }
    }

    .nav-bar-no-border {
        border: none !important;
    }
}
</style>
