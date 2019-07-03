<template>
    <div class="shop-list">
        <div
            class="col-xs-12 family-title"
        >
            <div class="thick-featured-border" />

            <div class="col-xs-6 featured-title">
                <p>
                    {{ title }}
                </p>
            </div>

            <div class="col-xs-6 see-more-featured">
                <button
                    class="btn-see-more-shop"
                    @click="showAllShop = true"
                >
                    Voir
                    <span class="circle">
                        {{ shops.length }}
                    </span>
                </button>
            </div>
        </div>

        <div class="col-xs-12 shop-tile-container">
            <TileShop
                v-for="shop in shopListFiltered"
                :key="shop.id"
                :shop-name="shop.nom_enseigne"
                :image-path="shop.path_file_logo_entreprise"
                class="col-md-3 col-sm-6 col-xs-12"
                @click="goToShop(shop)"
            />
        </div>
    </div>
</template>

<script>
import TileShop from '@/components/client/Tile/TileShop';

export default {
    name: 'ProductCategoryShopList',
    components: {
        TileShop
    },
    props: {
        shops: { type: Array, required: true },
        title: { type: String, required: true }
    },
    data: () => {
        return {
            showedShopQuantity: 8,
            showAllShop: false
        };
    },
    computed: {
        shopListFiltered () {
            let shopListToShow = [];

            if (this.showAllShop) {
                shopListToShow = this.shops;
            } else {
                shopListToShow = this.shops.slice(0, this.showedShopQuantity);
            }

            return shopListToShow;
        }
    },
    methods: {
        goToShop (shop) {
            this.$store.commit('SET_SHOP_NAME', shop.nom_enseigne);
            this.$store.commit('SET_CITY_SHOP', shop.ville);

            this.$router.push({
                name: 'shop',
                params: {
                    city: shop.ville,
                    shopname: shop.nom_enseigne
                }
            });
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_mixins';

.shop-list {
    @include font-size(1.6);
    font-weight: 500;
    padding: 0 15px;
    max-width: 1200px;
    margin: 0 auto;

    .family-title {
        margin: 0;
        padding: 0 0 20px 0;
        border-bottom: 1px solid rgba(217, 219, 224, 0.5);
        height: 50px;
        position: relative;

        .featured-title {
            justify-content: flex-start;
        }

        .see-more-featured {
            justify-content: flex-end;
        }

        .featured-title, .see-more-featured {
            padding: 0;
            height: 100%;
            margin: 0;
        }

        .thick-featured-border {
            position: absolute;
            width: 32px;
            height: 100%;
            top: 2px;
            left: 0;
            border-bottom: 2px solid rgba(0, 0, 0, 0.9);
        }

        p {
            @include font-size(1.8);
            font-weight: 500;
            margin: 0;
        }

        .btn-see-more-shop {
            @include font-size(1.4);
            background-color: transparent;
            border: none;
            color: $secondary-color;
            font-weight: 500;
            float: right;
            clear: both;

            &:hover {
                color : $primary-color;
                font-weight: 500;
            }

            .circle {
                background-color: $primary-color;
                border-radius: 100%;
                color: $white;
                display: inline-block;
                height: 20px;
                padding: 1px 2px 0 0;
                width: 20px;
            }
        }
    }

    .shop-tile-container {
        padding: 0;
        margin: 40px 0;
    }
}
</style>
