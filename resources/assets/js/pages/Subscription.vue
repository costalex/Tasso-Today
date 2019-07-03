<template>
    <div align="center">
        <div
            class="registration"
            align="center"
        >
            <h1>Inscription</h1>
            <div class="form-group">
                <input
                    id="firstname"
                    v-model="info_user.firstname"
                    class="custom-input-top"
                    placeholder="Prénom"
                    autocomplete="given-name"
                >

                <input
                    id="lastname"
                    v-model="info_user.lastname"
                    class="custom-input-middle"
                    placeholder="Nom"
                    autocomplete="family-name"
                >

                <input
                    id="phone"
                    v-model="info_user.phone"
                    class="custom-input-bottom"
                    placeholder="Mobile"
                    autocomplete="tel-national"
                >
            </div>

            <div class="form-group">
                <input
                    id="email"
                    v-model="info_user.email"
                    class="custom-input-top"
                    placeholder="Email"
                    autocomplete="email"
                >

                <input
                    id="password"
                    v-model="info_user.password"
                    class="custom-input-middle"
                    placeholder="Mot de passe"
                    type="password"
                >

                <input
                    id="confirm_password"
                    v-model="confirm_password"
                    class="custom-input-bottom"
                    placeholder="Confirmer mot de passe"
                    type="password"
                >
            </div>

            <div
                id="cgu_block"
                class="col-sm-12 col-md-12 double-input"
            >
                <div class="col-sm-2 col-md-2 checkbox">
                    <label>
                        <input
                            id="cgu_checkbox"
                            v-model="cgu"
                            name="cgu_checkbox"
                            type="checkbox"
                        >
                    </label>
                </div>

                <div class="col-sm-10 col-md-10">
                    <p class="regular_small_dark_openSans">
                        J'accepte les
                        <a
                            href="/conditions-generales-de-ventes"
                            target="_blank"
                        >
                            Conditions Générales de Ventes et d'Utilisation
                        </a>
                    </p>
                </div>
            </div>

            <div class="form-group">
                <button
                    class="btn registration-button"
                    @click="Inscription"
                >
                    S'INSCRIRE
                </button>
            </div>
        </div>
        <div>
            <p>Vous avez déjà un compte?</p>

            <button
                class="btn login-button"
                @click="$router.push({ name: 'loginPage' })"
            >
                SE CONNECTER
            </button>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import NotificationTypes from '@/class/NotificationTypes';

export default {
    name: 'Subscritpion',
    data: () => {
        return {
            info_user: {
                firstname: '',
                lastname: '',
                phone: '',
                email: '',
                password: ''
            },
            cgu: false,
            confirm_password: '',
            notificationTypes: new NotificationTypes()
        };
    },
    computed: {
        ...mapState({
            CookieAccepted: state => state.GeneralModule.CookieAccepted,
            InscriptionStatu: state => state.GeneralModule.InscriptionStatu
        })
    },
    watch: {
        InscriptionStatu: function () {
            if (this.InscriptionStatu) {
                this.$router.push({ name: 'loginPage' });
            }
        }
    },
    methods: {
        Inscription () {
            if (this.info_user.password === this.confirm_password) {
                if (this.cgu) {
                    if (this.CookieAccepted) {
                        this.$store.dispatch('INSCRIPTION_CLIENT', this.info_user)
                            .then(() => {
                                this.$router.push({ name: 'loginPage' });
                            });
                    } else {
                        this.$store.dispatch('NOTIFY', {
                            type: this.notificationTypes.ERROR,
                            message: 'Vous devez accepter le dépôt de cookie'
                        });
                    }
                } else {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.ERROR,
                        message: 'Vous devez accepter les conditions génerales de ventes'
                    });
                }
            } else {
                this.$store.dispatch('NOTIFY', {
                    type: this.notificationTypes.ERROR,
                    message: 'Les mots de passe ne correspondent pas'
                });
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.registration {
    @include prefix(box-shadow, $dark-shadow-box, webkit moz ms o);
    align-self: center;
    border: 1px solid $grey;
    border-radius: 5px;
    margin-bottom: 50px;
    height: auto;
    width: 300px;

    h1 {
        @include font-size(2.6);
        color: $primary-color;
        font-weight: 300;
        padding-bottom: 20px;
    }

    .custom-input {
        background-color: $light-grey;
        border: 1px solid $grey;
        border-radius: 5px;
        color: $dark-grey;
        font-weight: 300;
        height: 40px;
        text-decoration: none;
        text-indent: 10px;
        width: 270px;
    }

    .custom-input-top {
        background-color: $light-grey;
        border: 1px solid $grey;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        color: $dark-grey;
        font-weight: 300;
        height: 40px;
        text-decoration: none;
        text-indent: 10px;
        width: 270px;
    }

    .custom-input-middle {
        background-color: $light-grey;
        border: 1px solid $grey;
        border-top: 0;
        color: $dark-grey;
        height: 40px;
        font-weight: 300;
        text-decoration: none;
        text-indent: 10px;
        width: 270px;
    }

    .custom-input-bottom {
        background-color: $light-grey;
        border: 1px solid $grey;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        border-top: 0;
        color: $dark-grey;
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
    background-color: $red;
}

.registration-button {
    background-color: $primary-color;
}
</style>
