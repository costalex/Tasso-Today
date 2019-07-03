<template>
    <div
        id="MenuBoutique"
        class="menu-boutique"
    >
        <nav class="shop-nav">
            <ul>
                <li
                    v-for="(rayon, id_rayon) in Rayons"
                    v-if="rayon.sous_rayons.length > 0"
                    :key="id_rayon"
                    :class="{ 'current': select_rayon === id_rayon }"
                >
                    <h3
                        :class="{ 'blue-menu-selected': select_rayon === id_rayon }"
                        @click="selectRayon(id_rayon)"
                    >
                        {{ rayon.rayon_nom }}
                    </h3>
                    <ul
                        v-if="select_rayon === id_rayon"
                        class="sub-rayon"
                    >
                        <li
                            v-for="(subrayon, id_subrayon) in rayon.sous_rayons"
                            :key="id_subrayon"
                        >
                            <p
                                :class="{ 'blue-menu-selected': select_subrayon === id_subrayon }"
                                class="subrayon-title"
                                @click.stop="selectSubRayon(id_subrayon)"
                            >
                                {{ subrayon.sous_rayon_nom }}
                            </p>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'MenuBoutique',
    data: () => {
        return {
            select_subrayon: 0,
            select_rayon: 0,
            select_etagere: 0,
            navBarShop: null,
            menuBoutique: null,
            menuBoutiqueOffsetTop: 0
        };
    },
    computed: {
        ...mapState({
            Rayons: state => state.ClientStore.list_rayon
        })
    },
    mounted () {
        this.navBarShop = document.getElementById('NavBarShop');
        this.menuBoutique = document.getElementById('MenuBoutique');
        this.menuBoutiqueOffsetTop = this.menuBoutique.getBoundingClientRect().top + window.scrollY;
    },
    created () {
        window.addEventListener('scroll', this.handleScroll);
    },
    destroyed () {
        window.removeEventListener('scroll', this.handleScroll);
    },
    methods: {
        handleScroll () {
            if (this.menuBoutique !== null &&
                this.navBarShop !== null &&
                window.pageYOffset + this.navBarShop.offsetHeight > this.menuBoutiqueOffsetTop
            ) {
                this.menuBoutique.classList.add('sticky');
            } else if (this.menuBoutique !== null) {
                this.menuBoutique.classList.remove('sticky');
            }
        },
        selectRayon (index) {
            if (this.select_rayon !== index) {
                this.select_subrayon = 0;
                this.select_etagere = 0;
                this.$store.commit('SET_RAYON_CLIENT', index);
                this.$store.commit('SET_SOUS_RAYON_CLIENT', 0);
                this.$store.commit('SET_ETAGERE_AFF_BOUTIQUE_CLIENT');
            }

            this.select_rayon = index;
        },
        selectSubRayon (index) {
            if (this.select_subrayon !== index) {
                this.select_subrayon = index;
                this.select_etagere = 0;
                this.$store.commit('SET_SOUS_RAYON_CLIENT', index);
                this.$store.commit('SET_ETAGERE_AFF_BOUTIQUE_CLIENT');
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

@media screen and (min-width: 992px) {
    #MenuBoutique {
        &.sticky {
            position: fixed;
            top: 70px;
            z-index: 1000;
            width: 200px;
        }
    }
}

.menu-boutique {
    .shop-nav {
        z-index: 50;

        ul, li {
            list-style-type: none;
            margin: 0;
            padding: 0;

            h3, p {
                cursor: pointer;
            }
        }

        .subrayon-title {
            @include font-size(1.3);
            font-weight: 500;
            padding-left: 15px;

            &:hover, &:active {
                color: $primary-color;
                cursor: pointer;
                font-weight: 500;
            }
        }
    }

    @media screen and (max-width: 991px) {
        nav.shop-nav {
            position: relative;
            margin-bottom: 50px;
            min-height: 60px;

            ul {
                width: 100%;
                min-height: 40px;
                padding: 10px;
                top: 0;
                left: 0;
                background-color: #f7f7f8;

                h3 {
                    margin: 3px;
                }

                .sub-rayon {
                    border: none;

                    p {
                        margin-top: 10px;
                    }
                }
            }

            li {
                display: none;
                margin: 0;
            }

            .current {
                display: block;
            }

            a {
                display: block;
                padding: 5px 5px 5px 32px;
                text-align: left;
            }

            .current a {
                background: none;
                color: $dark-grey;
            }

            ul:hover {
                li {
                    display: block;
                    margin: 0 0 5px;
                }
            }

            &.right ul {
                left: auto;
                right: 0;
            }

            &.center ul {
                left: 50%;
                margin-left: -90px;
            }
        }
    }
}
</style>
