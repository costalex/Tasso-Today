<template>
    <div
        id="NewProduitsImage"
        class="col-md-12 col-sm12 col-xs-12 no-padding img-upload"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding">
            <div
                class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding"
                style="padding-left: 15px;"
            >
                <h4>Images</h4>
            </div>

            <div class="panel-body col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Image principale</h4>

                    <input
                        type="file"
                        @change="onFileChangePrincipal(0, $event)"
                    >
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4>Images secondaires</h4>
                    <input
                        type="file"
                        @change="onFileChangeSecondaire(0, $event)"
                    >

                    <input
                        type="file"
                        @change="onFileChangeSecondaire(1, $event)"
                    >
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'BodyNewProduitsImage',
    data: () => {
        return {
            ListImagePrincipal: [{ 0: '' }],
            ListImageSecondaire: [{ 0: '', 1: '' }]
        };
    },
    methods: {
        onFileChangePrincipal (num, e) {
            const files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            this.filesChangePrincipal(num, files[0]);
        },
        filesChangePrincipal (num, file) {
            const reader = new FileReader();

            reader.onload = (e) => {
                this.ListImagePrincipal[num] = e.target.result;
                this.$store.commit('SET_NEW_IMAGEPRINCIPAL', this.ListImagePrincipal);
            };
            reader.readAsDataURL(file);
        },
        onFileChangeSecondaire (num, e) {
            const files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            this.filesChangeSecondaire(num, files[0]);
        },
        filesChangeSecondaire (num, file) {
            const reader = new FileReader();

            reader.onload = (e) => {
                this.ListImageSecondaire[num] = e.target.result;
                this.$store.commit('SET_NEW_IMAGESECONDAIRE', this.ListImageSecondaire);
            };
            reader.readAsDataURL(file);
        }
    }
};
</script>

<style lang="scss" scoped>
.img-upload {
    @media (min-width: 992px) {
        padding-left: 15px;
    }
}
</style>
