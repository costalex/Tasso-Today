<template>
    <div
        id="VendorTopNav"
        @mouseleave="closeMenu"
    >
        <div class="page-name">
            {{ $route.meta.title }}
        </div>

        <div class="close-open-store">
            <div class="store-status">
                <div
                    :class="{
                        'store-status-open': (ShopStatus === 'OUVERT'),
                        'store-status-close': (ShopStatus === 'FERME_J'),
                        'store-status-stop': (ShopStatus === 'ARRET')
                    }"
                >
                    <div>
                        <p>{{ storeStatus }}</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="switch">
                    <input
                        v-model="storeOpened"
                        type="checkbox"
                        @change="updateStoreStatus"
                    >

                    <span
                        :style="{ 'background-color': sliderBgColor }"
                        class="slider round"
                    />
                </label>
            </div>
        </div>

        <div class="hello-store">
            <p>Bonjour,</p>
            {{ NameShop }}
        </div>

        <div
            class="btn-profile dropbutton vendor-profile"
            @mouseenter="showMenu"
        >
            <div
                v-click-outside="closeMenu"
                v-if="menuOpened"
                class="dropdwn-content"
                @mouseleave="menuOpened = false"
            >
                <a @click="itemSelected('Mon compte')">
                    Mon compte
                </a>

                <a
                    class="deconnexion"
                    @click="logout"
                >
                    Déconnexion
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'VendorTopNav',
    directives: {
        'click-outside': {
            bind: function (el, binding, vNode) {
                // Provided expression must evaluate to a function.
                if (typeof binding.value !== 'function') {
                    const compName = vNode.context.name;
                    let warn = `[Vue-click-outside:] provided expression '${binding.expression}' is not a function, but has to be`;
                    if (compName) {
                        warn += `Found in component '${compName}'`;
                    }

                    console.warn(warn);
                }

                // Define Handler and cache it on the element
                const bubble = binding.modifiers.bubble;
                const handler = (e) => {
                    if (bubble || (!el.contains(e.target) && el !== e.target)) {
                        binding.value(e);
                    }
                };
                el.__vueClickOutside__ = handler;

                // add Event Listeners
                document.addEventListener('click', handler);
            },
            unbind: function (el) {
                document.removeEventListener('click', el.__vueClickOutside__);
                el.__vueClickOutside__ = null;
            }
        }
    },
    data () {
        return {
            menuOpened: false,
            storeOpened: true
        };
    },
    computed: {
        ...mapState({
            NameShop: state => state.DetailsShopModule.enseigne_name,
            ShopStatus: state => state.DetailsShopModule.ShopStatus
        }),
        storeStatus () {
            let storeStatus = '';

            switch (this.ShopStatus) {
                case 'OUVERT':
                    storeStatus = 'Ouvert';
                    break;
                case 'FERME_J':
                    storeStatus = 'Fermé';
                    break;
                case 'ARRET':
                    storeStatus = 'Fermeture Prolongée';
                    break;
            }

            return storeStatus;
        },
        sliderBgColor () {
            let sliderBgColor = '#';

            switch (this.ShopStatus) {
                case 'OUVERT':
                    sliderBgColor += '7ED321';
                    break;
                case 'FERME_J':
                    sliderBgColor += 'D0021B';
                    break;
                case 'ARRET':
                    sliderBgColor += '88007F';
                    break;
            }

            return sliderBgColor;
        }
    },
    watch: {
        ShopStatus () {
            this.storeOpened = (this.ShopStatus === 'OUVERT');
        }
    },
    mounted: function () {
        this.storeOpened = (this.ShopStatus === 'OUVERT');
    },
    methods: {
        updateStoreStatus () {
            if (!this.storeOpened) {
                this.$store.dispatch('SET_UPDATE_STATUS_SHOP', 'FERME_J');
                this.storeOpened = false;
            } else {
                this.$store.dispatch('SET_UPDATE_STATUS_SHOP', 'OUVERT');
                this.storeOpened = true;
            }
        },
        closeMenu () {
            this.menuOpened = false;
        },
        showMenu () {
            this.menuOpened = true;
        },
        logout () {
            this.$store.dispatch('LOGOUT_USER')
                .then(() => {
                    this.$router.push({ name: 'home' });
                });
        },
        itemSelected (value) {
            if (value === 'Mon compte') {
                this.$router.push({ name: 'vendorProfile' });
            } else {
                this.$router.push({ name: 'vendorProduct' });
            }

            this.menuOpened = false;
        }
    }
};
</script>

<style lang="scss" scoped>
#VendorTopNav {
    display: flex;
    position: fixed;
    z-index: 10;
    top: 0;
    justify-content: flex-end;
    align-items: center;
    height: 50px;
    width: 100%;
    padding-right: 20px;
    background-color: #fff;
    -webkit-box-shadow: 0 2px 6px 0 rgba(0,0,0,0.3);
    -moz-box-shadow: 0 2px 6px 0 rgba(0,0,0,0.3);
    box-shadow: 0 2px 6px 0 rgba(0,0,0,0.3);
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    margin-bottom: 50px;

    .close-open-store {
        display: flex;
        position: relative;
        align-items: center;
        height: 100%;
        margin-right: 20px;

        .switch {
            margin: 7px 10px 0 10px;
        }

        p {
            margin: 0;
            text-transform: uppercase;
            font-size: 12px;
        }

        .store-status {
            margin-left: 15px;

            div {
                border-radius: 2px;
                min-width:75px;
                height:25px;
                display: flex;
                position: relative;
                justify-content: center;
                align-items: center;

                p {
                    text-transform: uppercase;
                    color: #FFFFFF;
                    font-size: 15px;
                    margin-top: 2px;
                    padding: 0 8px;
                }
            }

            .store-status-open div {
                background-color:#7ED321;
            }

            .store-status-close div {
                background-color:#D0021B;
            }

            .store-status-stop div {
                background-color:#88007F;
            }
        }
    }

    .vendor-profile {
        background-color:#1D2531;
        height: 38px;
        width: 38px;
        border-radius: 2px;
        background-image: url(/storage/bobby_images/marketplace-client/profile-icon.svg);
        background-repeat: no-repeat;
        background-position: center;

        .dropdwn-content {
            right: 20px;
            top: -28px;
        }
    }

    .vendor-profile:hover {
        background-color: #2C343F;
    }

    .hello-store {
        margin-right: 25px;
        padding-left: 10px;
        border-left: 1px solid #000;
        max-width: 300px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;

        p {
            text-transform: uppercase;
            font-size: 10px;
            font-weight: 500;
            color:#6D6D6D;
            margin: 0;
        }
    }

    .page-name {
        position: absolute;
        left: 110px;
        text-transform: capitalize;
        font-size: 18px;
    }
}
</style>
