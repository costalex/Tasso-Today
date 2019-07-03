<template>
    <div align="center">
        <link
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-social/5.1.1/bootstrap-social.min.css"
            rel="stylesheet"
            type="text/css"
        >

        <div align="center">
            <div
                v-if="!password_miss"
                class="connexion"
                align="center"
            >
                <h1>Connexion</h1>

                <div>
                    <div class="form-group">
                        <input
                            id="username"
                            v-model="c_email"
                            class="custom-input-top"
                            name="username"
                            placeholder="Email"
                        >

                        <input
                            id="password"
                            v-model="c_password"
                            class="custom-input-bottom"
                            name="password"
                            type="password"
                            placeholder="Mot de passe"
                        >
                    </div>

                    <div class="error-msg">
                        <p
                            v-if="showCookieError && !CookieAccepted"
                            class="error-cookie"
                        >
                            Veuillez accepter les cookies afin de vous connecter
                        </p>
                    </div>

                    <br>

                    <a
                        class="hidden_link"
                        style="cursor: pointer;"
                        @click="modif_password(true)"
                    >
                        <p> Mot de passe oublié ?</p>
                    </a>

                    <div
                        class="form-group"
                    >
                        <button
                            class="btn login-button"
                            @click="DisplayNameUser"
                        >
                            SE CONNECTER
                        </button>
                    </div>
                </div>

                <template v-if="CookieAccepted && applicationEnv !== 'prod'">
                    <div
                        class="col-md-12 col-sm-12"
                        style="padding-top: 20px;"
                    >
                        <div class="form-group">
                            <a
                                href="/api/login/facebook"
                                class="btn btn-block btn-social btn-facebook"
                            >
                                <span class="fa fa-facebook" />
                                Se connecter avec Facebook
                            </a>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <a
                                href="/api/login/google"
                                class="btn btn-block btn-social btn-google"
                            >
                                <span class="fa fa-google" />
                                Se connecter avec Google
                            </a>
                        </div>
                    </div>
                </template>
            </div>

            <div v-else>
                <div class="form-group">
                    <input
                        id="email"
                        v-model="c_email"
                        class="custom-input"
                        name="email"
                        placeholder="Email"
                    >
                </div>

                <br>

                <div class="form-group">
                    <button
                        class="btn confirmation-button"
                        @click="send_new_password"
                    >
                        ENVOYER
                    </button>
                </div>
            </div>

            <div
                class="col-md-12 col-sm-12"
                style="padding-top: 10px;"
            >
                <p>Vous n'avez pas de compte ?</p>

                <button
                    class="btn registration-button"
                    @click="$router.push({ name: 'registrationPage' })"
                >
                    S'INSCRIRE
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState, mapGetters } from 'vuex';
import env from '@/env';

export default {
    name: 'Connection',
    data: () => {
        return {
            password_miss: false,
            c_email: '',
            c_password: '',
            applicationEnv: env.APP_ENV,
            showCookieError: false
        };
    },
    computed: {
        ...mapState({
            CookieAccepted: state => state.GeneralModule.CookieAccepted
        }),
        ...mapGetters({
            TypeUser: 'getTypeUser'
        })
    },
    mounted: function () {
        this.$store.commit('SET_INSCRIPTION_STATU', false);
    },
    methods: {
        modif_password (value) {
            this.password_miss = value;
        },
        send_new_password () {
            this.password_miss = false;
            this.$store.dispatch('SEND_LOST_PASSWORD', { email: this.c_email });
        },
        DisplayNameUser () {
            if (this.CookieAccepted) {
                this.showCookieError = false;

                this.$store.dispatch('CONNECT_USER', { email: this.c_email, password: this.c_password })
                    .then(() => {
                        switch (this.TypeUser) {
                            case 'Client':
                                this.$router.push({ name: 'familliesList' });
                                break;
                            case 'Admin':
                                this.$router.push({ name: 'AdminProduct' });
                                break;
                            case 'Vendeur':
                                this.$router.push({ name: 'vendorProduct' });
                                break;
                        }
                    });
            } else {
                this.showCookieError = true;
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.connexion {
    @include prefix(box-shadow, $shadow-box, webkit moz ms o);
    align-self: center;
    border: 1px solid $grey;
    border-radius: 5px;
    height: auto;
    margin-bottom: 50px;
    width: 300px;

    h1 {
        @include font-size(2.6);
        color: $primary-color;
        font-weight: 400;
        padding-bottom: 20px;
    }

    .error-msg {
        width: 90%;

        p {
            color: red;
        }
    }

    .custom-input {
        @include font-size(1.5);
        @include prefix(color, $dark-grey, webkit moz ms o);
        background-color: $white;
        color: $dark-grey;
        font-weight: 300;
        padding-bottom: 15px;
        text-decoration: none;
        text-indent: 10px;
        width: 270px;
    }

    .custom-input-top {
        @include font-size(1.5);
        @include prefix(color, $dark-grey, webkit moz ms o);
        background-color: $light-grey;
        border: 1px solid $grey;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        font-weight: 300;
        height: 40px;
        text-decoration: none;
        text-indent: 10px;
        width: 270px;
    }

    .custom-input-bottom {
        @include font-size(1.5);
        @include prefix(color, $dark-grey, webkit moz ms o);
        background-color: $light-grey;
        border: 1px solid $grey;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        border-top: 0;
        font-weight: 300;
        height: 40px;
        text-decoration: none;
        text-indent: 10px;
        width: 270px;
    }

    p {
        @include font-size(1.5);
    }
}

.registration-button,
.login-button {
    @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
    @include font-size(1.4);
    align-items: center;
    align-self: center;
    border-radius: 5px;
    color: $white;
    display: inline-block;
    font-family: $default-font-family;
    font-weight: 300;
    margin-bottom: 10px;
    margin-top: 15px;
    padding: 10px;
    text-align: center;
    text-decoration: none;

    &:hover {
        @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
        color: $white;
        transition: box-shadow .3s, background-color .3s, color .3s, transform .3s;
        transition-duration: .3s, .3s, .3s, .3s;
    }
}

.login-button {
    background-color: $primary-color;
}

.registration-button {
    background-color: $red;
}
</style>
