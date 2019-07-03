<template>
    <div
        id="NewProduitsTitle"
        class="col-md-12 col-sm-12 col-xs-12 no-padding product-title"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding">
            <div
                class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding"
                style="padding-left: 15px;"
            >
                <h4>Titre et description</h4>
            </div>

            <div class="panel-body col-md-12 col-sm-12 col-xs-12 no-padding">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Titre</h4>
                    <h3 id="title">{{ ProduitTitle }}</h3>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Description</h4>
                    <textarea
                        v-model="ModifProduitDescription"
                        class="textarea-description-enseigne"
                        rows="3"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ModifyProductBodyTilte',
    computed: {
        ModifProduitDescription: {
            set (value) {
                this.$store.commit('SET_MODIF_PRODUIT_DESCRIPTION', value);
            },
            get () {
                return this.$store.getters.getModifProduitDescription;
            }
        },
        ProduitTitle: {
            get () {
                return this.$store.getters.getModifProduitTitle;
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';

.textarea-description-enseigne {
    border-color: $dark-grey;
    border-radius: 3px;
    width: 100%;
    height: 100%;
    resize: none;
}

.product-title {
    float: left;

    .panel-body {
        height: 310px;

        #title {
            border-bottom: 1px solid $grey;
        }
    }
}
</style>
