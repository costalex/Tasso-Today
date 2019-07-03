<template>
    <div
        id="TopnavClient"
        class="topnav-client"
    >
        <div class="nav-tasso-logo">
            <img
                src="/storage/bobby_images/icons/logo-beta.svg"
                @click="gotolandingpage"
            >
        </div>

        <div
            v-if="TypeAccount === 'Guest'"
            class="nav-connect"
        >
            <a @click="$router.push({ name: 'loginPage' })">
                <button
                    class="btn btn-login"
                    autofocus
                    @click="save_panier"
                >
                    Connexion
                </button>
            </a>

            <a @click="$router.push({ name: 'registrationPage' })">
                <button class="btn btn-registration">
                    Inscription
                </button>
            </a>
        </div>

        <div
            v-else-if="TypeAccount === 'Client'"
            class="nav-account"
        >
            <div>
                <button
                    class="btn btn-cart"
                    @click="gotoRecapPanier"
                >
                    <img src="/storage/bobby_images/marketplace-client/icon-cart-black.svg">
                    <span>{{ GeneralPanier.length }}</span>
                </button>

                <img
                    class="dropbutton btn-profile"
                    src="/storage/bobby_images/marketplace-client/icon-profile.svg"
                    @mouseenter="MenuAff"
                >

                <div
                    v-click-outside="MenuClose"
                    v-if="open"
                    class="dropdwn-content"
                    @mouseleave="mouseLeave"
                >
                    <a @click="itemSelect('Mon compte')">
                        Mon compte
                    </a>

                    <a
                        class="deconnexion"
                        @click="Logout"
                    >
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'TopnavClient',
    directives: {
        'click-outside': {
            bind: function (el, binding, vNode) {
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
            unbind: function (el) {
                document.removeEventListener('click', el.__vueClickOutside__);
                el.__vueClickOutside__ = null;
            }
        }
    },
    data: () => {
        return {
            open: false
        };
    },
    computed: {
        ...mapState({
            TypeAccount: state => state.GeneralModule.TypeUser,
            GeneralPanier: state => state.CartModule.panier_general,
            CookieAccepted: state => state.GeneralModule.CookieAccepted
        })
    },
    methods: {
        Logout () {
            this.$store.dispatch('LOGOUT_USER')
                .then(() => {
                    this.$router.push({ name: 'home' });
                });
        },
        MenuClose () {
            this.open = false;
        },
        MenuAff () {
            this.open = true;
        },
        gotoRecapPanier () {
            this.save_panier();

            this.$router.push({ name: 'cartRecap' });
        },
        gotolandingpage () {
            this.save_panier();

            switch (this.TypeAccount) {
                case 'Guest':
                    this.$router.push({ name: 'home' });
                    break;
                case 'Client':
                    this.$router.push({ name: 'familliesList' });
                    break;
            }
        },
        save_panier () {
            if (this.CookieAccepted) {
                this.$session.set('panier', this.GeneralPanier);
            }
        },
        mouseLeave () {
            if (this.open) {
                this.open = false;
            }
        },
        itemSelect () {
            this.$router.push({ name: 'profile' });

            this.mouseLeave();
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

#TopnavClient {
    margin: 0 auto;
    display: flex;
    position: relative;
    min-height: 72px;
    max-width: 1200px;
    width: 100%;
    background-color: transparent;
    z-index: 1000;

    &.topnav-client {
        @include font-size(1.7);

        .nav-tasso-logo {
            display: flex;
            position: relative;
            width: 100%;
            justify-content: flex-start;

            img {
                margin: 0;
                cursor: pointer;
            }
        }

        .nav-connect {
            display: flex;
            flex-direction: row;

            a {
                display: flex;
                text-decoration: none;
            }

            .btn-login,
            .btn-registration {
                @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
                @include font-size(1.3);
                align-items: center;
                align-self: center;
                border-radius: 5px;
                color: $white;
                display: inline-block;
                font-family: $default-font-family;
                font-weight: 400;
                text-align: center;
                text-decoration: none;

                &:hover {
                    @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
                    color: $white;
                }
            }

            .btn-login {
                background-color: $pastel-green;
                margin-right: 10px;
            }

            .btn-registration {
                background-color: $primary-color;
            }
        }

        .nav-account {
            display: flex;
            position: relative;
            width: 100%;
            justify-content: flex-end;

            div:first-child {
                display: flex;
                justify-content: flex-end;
            }

            .dropdwn-content {
                margin-top: 55px;
            }

            .btn-cart {
                background-color: transparent;
                border: none;
                color: $secondary-color;
                cursor: pointer;
                font-weight: 500;
                padding: 0 17px 0 0;
                margin: 0;
                outline: none;
                box-shadow: none;

                span {
                    padding-left: 5px;
                    vertical-align: sub;
                }

                &:hover {
                    color: $primary-color;

                    img {
                        content: url('/storage/bobby_images/marketplace-client/icon-cart-hover.svg');
                    }
                }

                &:focus {
                    outline: none;
                    border:none;
                    box-shadow: none;
                }
            }

            .btn-profile {
                border: none;
                color: $secondary-color;
                cursor: pointer;
                font-weight: 500;
                padding-right: 15px;
                text-decoration: none;
                vertical-align: middle;

                &:hover {
                    color: $primary-color;
                    content: url('/storage/bobby_images/marketplace-client/icon-profile-hover.svg');
                }
            }
        }
    }
}
</style>
