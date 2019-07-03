<template>
    <div
        id="ModifyProductBody"
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
            <ModifProduitDimention/>
        </div>

        <div
            align="right"
            class="col-md-12 col-sm-12 col-xs-12 no-padding"
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
import ModifProduitParameter from '@/components/admin/PageModifyProduct/ModifyProductParameter';
import ModifProduitTitle from '@/components/admin/PageModifyProduct/ModifyProductTitle';
import ModifProduitImage from '@/components/admin/PageModifyProduct/ModifyProductImage';
import ModifProduitDimention from '@/components/admin/PageModifyProduct/ModifyProductDimension';

export default {
    name: 'ModifyProductBody',
    components: {
        ModifProduitParameter,
        ModifProduitTitle,
        ModifProduitImage,
        ModifProduitDimention
    },
    methods: {
        sendNewProduit () {
            this.$store.dispatch('SET_ADD_MARQUE_FOR_MODIF')
                .then(() => {
                    this.$store.dispatch('SEND_INFO_MODIF_PRODUIT')
                        .then(() => {
                            this.$router.push({ name: 'AdminProduct' });
                        });
                });
        },
        CancelNewProduit () {
            this.$store.commit('RESET_INFO_MODIF_PRODUIT');

            this.$router.push({ name: 'AdminProduct' });
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
