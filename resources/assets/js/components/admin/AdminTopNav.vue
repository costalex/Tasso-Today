<template>
    <div
        id="AdminTopNav"
        @mouseleave="menuOpened = false"
    >
        <div class="page-name">
            {{ $route.meta.title }}
        </div>

        <div class="hello-store">
            <p>Bonjour</p>
        </div>

        <div
            class="btn-profile dropbutton admin-profile"
            @mouseenter="showMenu"
        >
            <div
                v-click-outside="closeMenu"
                v-if="menuOpened"
                class="dropdwn-content"
                @mouseleave="menuOpened = false"
            >
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
export default {
    name: 'AdminTopNav',
    directives: {
        'click-outside': {
            bind: function (el, binding, vNode) {
                if (typeof binding.value !== 'function') {
                    const compName = vNode.context.name;
                    let warn = `[Vue-click-outside:] provided expression '${binding.expression}'
                        is not a function, but has to be`;
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
    data () {
        return {
            menuOpened: false,
            options: [
                { value: 'Parametre' },
                { value: 'Deconnection' }
            ]
        };
    },
    methods: {
        logout () {
            this.$store.dispatch('LOGOUT_USER')
                .then(() => {
                    this.$router.push({ name: 'home' });
                });
        },
        closeMenu () {
            this.menuOpened = false;
        },
        showMenu () {
            this.menuOpened = true;
        }
    }
};
</script>

<style lang="scss" scoped>
#AdminTopNav {
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
    -webkit-box-shadow: 0px 2px 6px 0px rgba(0,0,0,0.3);
    -moz-box-shadow: 0px 2px 6px 0px rgba(0,0,0,0.3);
    box-shadow: 0px 2px 6px 0px rgba(0,0,0,0.3);
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;

    .admin-profile {
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

    .admin-profile:hover {
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
