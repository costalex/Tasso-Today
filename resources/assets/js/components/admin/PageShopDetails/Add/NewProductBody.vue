<template>
    <div
        id="BoddyNewProduits"
        class="col-md-12 col-sm-12 col-xs-12 no-padding add-product"
    >
        <div
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
            align="right"
        >
            <button
                class="btn cancel-btn"
                @click="CancelNewProduit"
            >
                annuler
            </button>

            <button
                class="btn validate-btn"
                @click="sendNewProduit"
            >
                enregistrer
            </button>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
            <NewProduitParameter/>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
            <div class="col-md-6 col-sm-12 col-xs-12 no-padding">
                <NewProduitTitle/>
            </div>

            <div class="col-md-6 col-sm-12 col-xs-12 no-padding">
                <NewProduitImage/>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
            <NewProduitDimention/>
        </div>

        <div
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
            align="right"
        >
            <button
                class="btn cancel-btn"
                @click="CancelNewProduit"
            >
                annuler
            </button>

            <button
                class="btn validate-btn"
                @click="sendNewProduit"
            >
                enregistrer
            </button>
        </div>
    </div>
</template>

<script>
import NewProduitParameter from './NewProductParameter';
import NewProduitTitle from './NewProductTitle';
import NewProduitImage from './NewProductImage';
import NewProduitDimention from './NewProductDimension';

export default {
    name: 'BodyNewProduits',
    components: {
        NewProduitParameter,
        NewProduitTitle,
        NewProduitImage,
        NewProduitDimention
    },
    methods: {
        sendNewProduit () {
            this.$store.dispatch('SET_ADD_MARQUE');
        },
        CancelNewProduit () {
            this.$store.commit('SET_NEWPRODUIT');
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.add-product {
    .cancel-btn {
        @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
        @include font-size(1.2);
        align-items: center;
        align-self: center;
        background-color: $white;
        border-radius: 5px;
        color: $black;
        display: inline-block;
        font-family: $default-font-family;
        font-weight: 500;
        margin: 10px;
        text-align: center;
        text-decoration: none;

        &:hover {
            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
            color: $black;
        }
    }
    .validate-btn {
        @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
        @include font-size(1.2);
        align-items: center;
        align-self: center;
        background-color: $primary-color;
        border-radius: 5px;
        color: $white;
        display: inline-block;
        font-family: $default-font-family;
        font-weight: 500;
        margin: 10px;
        text-align: center;
        text-decoration: none;

        &:hover {
            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
            color: $white;
        }
    }
}
</style>
