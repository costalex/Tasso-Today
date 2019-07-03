<template>
    <div
        id="NewProduitsTitle"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
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
                    <h4 style="padding-left: 10px;">Titre</h4>

                    <input
                        v-model="NewProduitTitle"
                        class="titre-description-Shop"
                        type="text"
                    >
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Description</h4>
                    <textarea
                        v-model="NewProduitDescription"
                        class="textarea-description-Shop"
                        rows="3"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BodyNewProduitsTitle',
    computed: {
        NewProduitTitle: {
            set (value) {
                this.$store.commit('SET_NEW_TITLE', value);
            },
            get () {
                return '';
            }
        },
        NewProduitDescription: {
            set (value) {
                this.$store.commit('SET_NEW_DESCRIPTION', value);
            },
            get () {
                return '';
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.textarea-description-Shop {
    border: none;
    border: 1px solid #9b9b9b;
    border-radius: 3px;
    width: 100%;
    height: 180px;
    resize: none;
}
</style>
