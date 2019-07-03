<template>
    <div
        id="BoddyProfile"
        class="profile-block"
    >
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

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Informations client</h4>
            </div>

            <div class="panel-body">
                <div class="col-sm-6 col-xs-12">
                    <h3>Prénom </h3>
                    <input
                        v-model="User.prenom"
                        placeholder="Saisissez votre prénom"
                        required
                    >

                    <h3>Nom </h3>
                    <input
                        v-model="User.nom"
                        placeholder="Saisissez votre nom"
                        required
                    >

                    <h3>Téléphone </h3>
                    <input
                        v-model="User.telephone"
                        placeholder="Saisissez votre numéro de portable"
                        required
                    >

                    <div
                        class="col-xs-12"
                        style="margin-bottom: 30px; padding: 0;"
                    >
                        <h3>Adresse :</h3>
                        <AddressBarProfile/>
                    </div>

                    <h3>E-mail </h3>
                    <p>{{ User.user.email }}</p>

                    <div
                        class="col-sm-12 col-xs-12 no-padding"
                        align="center"
                        style="margin-bottom: 10px;"
                    >
                        <button
                            class="btn btn-save-infos"
                            @click="save_profile(User, Address_select)"
                        >
                            Enregistrer les informations
                        </button>
                    </div>
                </div>

                <div class="col-sm-6 col-xs-12">
                    <div class="col-xs-12">
                        <div
                            class="col-xs-12 paiement-infos"
                            style="padding: 0; margin: 0;"
                        >
                            <h3>Informations de paiment</h3>

                            <StripeCard/>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <h3>E-mail </h3>
                        <input
                            v-model="email"
                            placeholder="Nouvel e-mail"
                            required
                        >

                        <h3>Nouveau mot de passe</h3>
                        <div class="new-password">
                            <input
                                v-model="new_pass"
                                placeholder="Nouveau mot de passe"
                                type="password"
                                required
                            >

                            <input
                                v-model="verif_new_pass"
                                placeholder="Verifier nouveau mot de passe"
                                type="password"
                                required
                            >
                        </div>

                        <div
                            class="col-sm-6 col-xs-12 no-padding"
                            align="center"
                            style="margin-top: 30px; margin-bottom: 10px;"
                        >
                            <button
                                class="btn btn-save-infos"
                                style="white-space: normal; word-wrap: break-word;"
                                @click="save_connetion_info"
                            >
                                Enregistrer informations de connexion
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    class="col-xs-12 no-padding"
                    style="margin-top: 50px; margin-bottom: 10px;"
                >
                    <button
                        class="btn btn-delete-account"
                        @click="delete_profile"
                    >
                        Supprimer compte
                    </button>

                    <p>Des questions à propos de la RGPD ? Ecrivez-nous : contact@tasso.today</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import StripeCard from '@/components/client/Stripe/StripeCard';
import AddressBarProfile from '@/components/client/PageProfile/DefaultAddressBarProfile';

export default {
    name: 'Profile',
    components: {
        StripeCard,
        AddressBarProfile
    },
    data: () => {
        return {
            change_card: false,
            add_card: false,
            new_pass: '',
            verif_new_pass: '',
            email: ''
        };
    },
    computed: {
        ...mapState({
            User: state => state.GeneralModule.UserInfo,
            Address_select: state => state.GeneralModule.Address,
            CookieAccepted: state => state.GeneralModule.CookieAccepted
        })
    },
    methods: {
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
        save_profile (value, address) {
            this.$store.commit('SET_ADDRESS', address);
            this.$store.commit('SET_PRENOM', value.prenom);
            this.$store.commit('SET_NOM', value.nom);
            this.$store.commit('SET_TELEPHONE', value.telephone);
            this.$store.commit('SET_USER', value);
            this.$store.dispatch('UPDATE_USER');
        },
        save_connetion_info () {
            if (this.new_pass === this.verif_new_pass && this.new_pass !== '') {
                this.$store.dispatch('UPDATE_USER_CONNECTION', { password: this.new_pass });
                this.new_pass = '';
                this.verif_new_pass = '';
            }

            if (this.email !== '') {
                this.$store.dispatch('UPDATE_USER_CONNECTION', { email: this.email });
                this.email = '';
            }
        },
        delete_profile () {
            this.$store.dispatch('DELETE_ACCOUNT')
                .then(() => {
                    this.$router.push({ name: 'home' });
                });
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.profile-block {
    margin: 0 auto;
    width: 60%;

    @media (min-width: 0px ) and (max-width: 767px ) {
        width: 90%;
    }

    @media (min-width: 1700px) {
        width: 50%;
    }

    .back-to-families {
        margin-bottom: 10px;
    }

    .panel-heading {
        @include font-size(1.4);
        font-style: italic;
        height: fit-content;
        h4 {
            margin: 0;
            vertical-align: middle;
        }
    }

    .panel-body {
        padding-top: 20px;
        p {
            @include font-size(1.2);
            color: $secondary-color;
            margin-top: 10px;
        }

        input {
            @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
            border: 1px solid $grey;
            border-radius: 3px;
            width: 200px;
        }

        .new-password {
            input {
                display: block;
                margin-bottom: 3px;
            }
        }

        .btn-save-infos {
            @include font-size(1.5);
            @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
            background-color: $pastel-green;
            color: $white;
            &:hover {
                @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
            }
        }

        .btn-delete-account {
            @include font-size(1.5);
            @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
            background-color: $red;
            color: $white;
            &:hover {
                @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
            }
        }

        .paiement-infos {
            margin-top: 20px;

            @media (min-width: 0px) and (max-width: 767px) {
                margin: 0 0 100px 0;
            }
        }
    }
}
</style>
