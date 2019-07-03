<template>
    <div
        id="ModifProduitsImage"
        class="col-md-12 col-sm-12 col-xs-12 no-padding img-upload"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding">
            <div
                class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding"
                style="padding-left: 15px;"
            >
                <h4>Images</h4>
            </div>

            <div v-if="IamgePrincipal">
                <div class="panel-body col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Image principale</h4>
                        <img :src="IamgePrincipal[0].image_miniature[1]">
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h4>Images secondaires</h4>
                        <div
                            v-if="ImageSecondaire !== ''"
                            class="col-md-12 col-sm-12 cl-xs-12 no-padding"
                        >
                            <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                <img
                                    v-if="ImageSecondaire !== '' && ImageSecondaire[0]"
                                    :src="ImageSecondaire[0].image_miniature[1]"
                                >
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                                <img
                                    v-if="ImageSecondaire !== '' && ImageSecondaire[1]"
                                    :src="ImageSecondaire[1].image_miniature[1]"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'ModifyProductBodyImage',
    computed: {
        IamgePrincipal: {
            get () {
                return this.$store.getters.getImagePrincipalModifProduit;
            }
        },
        ImageSecondaire: {
            get () {
                return this.$store.getters.getImageSecondaireModifProduit;
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.img-upload {
    float: right;
    padding-left: 15px;
    @media (max-width: 991px) {
        padding-left: 0;
    }
}
</style>
