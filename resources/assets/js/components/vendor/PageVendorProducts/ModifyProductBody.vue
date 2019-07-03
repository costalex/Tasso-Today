<template>
    <div
        id="BoddyModifProduits"
        class="col-md-12 col-sm-12 col-xs-12 no-padding modif-product"
    >
        <div
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
            align="right"
        >
            <button
                class="btn cancel-btn"
                @click="cancelModification"
            >
                Annuler
            </button>

            <button
                class="btn validate-btn"
                @click="saveModification"
            >
                Enregistrer
            </button>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
            <ModifProduitParameter/>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
            <div class="col-md-6 col-sm-12 col-xs-12 no-padding">
                <ModifProduitTitle/>
            </div>

            <div class="col-md-6 col-sm-12 col-xs-12 no-padding">
                <ModifProduitImage/>
            </div>
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
            <ModifProduitDetails/>
        </div>

        <div
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
            align="right"
        >
            <button
                class="btn cancel-btn"
                @click="cancelModification"
            >
                Annuler
            </button>

            <button
                class="btn validate-btn"
                @click="saveModification"
            >
                Enregistrer
            </button>
        </div>
    </div>
</template>

<script>
import ModifProduitParameter from './ModifyProductBodyParameter';
import ModifProduitTitle from './ModifyProductBodyTitle';
import ModifProduitImage from './ModifyProductBodyImages';
import ModifProduitDetails from './ModifyProductBodyDetails';

export default {
    name: 'BodyNewProduits',
    components: {
        ModifProduitParameter,
        ModifProduitTitle,
        ModifProduitImage,
        ModifProduitDetails
    },
    methods: {
        cancelModification () {
            this.$store.dispatch('SET_ENSEIGNE_DETAILS');
            this.$store.commit('SET_MODIF_PRODUIT');
        },
        saveModification () {
            this.$store.dispatch('SEND_PRODUIT_MODIF');
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.modif-product {
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
