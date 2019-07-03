<template>
    <div
        id="ShopUploaderImage"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding">
            <div class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding">
                <div
                    class="col-md-6 col-sm-6 col-xs-6 no-padding"
                    align="left"
                    style="padding-left: 15px; padding-top: 2%;"
                >
                    <p>Image e-boutique</p>
                </div>
                <div
                    class="col-md-6 col-sm-6 col-xs-6 no-padding"
                    align="right"
                >
                    <button
                        v-if="!modif_details"
                        class="btn btn-edit-infos-enseigne"
                        @click="Modif"
                    >
                        Modifier
                    </button>

                    <template v-else>
                        <button
                            class="btn btn-edit-infos-enseigne"
                            @click="cancelModification"
                        >
                            Annuler
                        </button>

                        <button
                            class="btn btn-edit-infos-enseigne"
                            @click="SendFile"
                        >
                            Enregistrer
                        </button>
                    </template>
                </div>
            </div>

            <div class="panel-body col-md-12 col-sm-12 col-xs-12">
                <div class="col-xs-8 no-padding">
                    <p>Photo bannière:</p>
                    <img
                        v-if="!modif_details"
                        :src="BanniereFile"
                        class="img-banniere"
                    >
                    <template v-else>
                        <input
                            type="file"
                            @change="onFileChange(0, $event)"
                        >

                        <img
                            v-show="Bannere !== ''"
                            id="Bannere"
                            class="img-banniere"
                            src="#"
                        >
                    </template>
                </div>

                <div class="col-xs-4 no-padding">
                    <p>Photo profil:</p>
                    <img
                        v-if="!modif_details"
                        :src="ProfileImageFile"
                        class="img-profile"
                    >
                    <template v-else>
                        <input
                            type="file"
                            @change="onFileChangePrincipal(0, $event)"
                        >

                        <img
                            v-show="ListImagePrincipal !== ''"
                            id="logo"
                            class="img-profile"
                            src="#"
                        >
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'ShopUploaderImage',
    data: () => {
        return {
            modif_details: false,
            Bannere: '',
            ListImagePrincipal: ''
        };
    },
    computed: {
        ...mapState({
            BanniereFile: state => state.DetailsShopModule.BannerImage,
            ProfileImageFile: state => state.DetailsShopModule.ProfileImage
        })
    },
    watch: {
        Bannere () {
            const element = document.getElementById('Bannere');
            element.src = this.Bannere;
        },
        ListImagePrincipal () {
            const element = document.getElementById('logo');
            element.src = this.ListImagePrincipal;
        }
    },
    methods: {
        Modif () {
            this.modif_details = true;
        },
        SendFile () {
            this.modif_details = false;
            this.$store.dispatch('SEND_BANNER_AND_PROFILE_IMAGE');
            this.Bannere = '';
            this.ListImagePrincipal = '';
        },
        cancelModification () {
            this.modif_details = false;
            this.Bannere = '';
            this.ListImagePrincipal = '';

            this.$store.commit('SET_NEW_BANNER_IMAGE', '');
            this.$store.commit('SET_NEW_PROFILE_IMAGE', '');
        },
        onFileChange (num, e) {
            const files = e.target.files || e.dataTransfer.files;
            if (!files.length) {
                return;
            }
            this.filesChange(num, files[0]);
        },
        filesChange (num, file) {
            const reader = new FileReader();

            reader.onload = (e) => {
                this.Bannere = e.target.result;
                this.$store.commit('SET_NEW_BANNER_IMAGE', this.Bannere);
            };
            reader.readAsDataURL(file);
        },
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
                this.ListImagePrincipal = e.target.result;
                this.$store.commit('SET_NEW_PROFILE_IMAGE', this.ListImagePrincipal);
            };
            reader.readAsDataURL(file);
        }
    }
};
</script>

<style lang="scss" scoped>
#ShopUploaderImage {
    .panel-body {
        .img-banniere {
            width: 600px;
            height: 300px;
        }

        .img-profile {
            width: 310px;
            height: 200px;
        }

        p {
            font-weight: 500;
        }
    }
}
</style>
