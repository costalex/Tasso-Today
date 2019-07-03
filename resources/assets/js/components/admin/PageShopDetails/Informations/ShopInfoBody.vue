-<template>
    <div
        id="ShopInformation"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
    >
        <div
            id="Shop_Name"
            class="col-md-12 col-sm-12 col-xs-12 Shop-activation"
        >
            <div
                class="entreprise-info col-md-6 col-sm-6 col-xs-6"
                align="left"
            >
                <p>Nom : <i>{{ Shop_name }}</i></p>
                <p>Référence : <i>{{ Shop_ref }}</i></p>
                <p>Statut : <i>{{ Shop_status }}</i></p>
                <p>Statut du compte : <i>{{ button_Activation.status }}</i></p>
            </div>

            <div class="entreprise-open-close col-md-6 col-sm-6 col-xs-6 no-padding">
                <button
                    v-if="button_Activation.status === 'ACTIVE'"
                    class="btn btn-danger pull-right"
                    @click="activation('BAN')"
                >
                    Désactivé le compte
                </button>

                <button
                    v-else
                    class="btn btn-success pull-right"
                    @click="activation('ACTIVE')"
                >
                    Activer le compte
                </button>

                <button
                    v-if="Shop_status === 'FERME'"
                    class="btn btn-success btn-open-close pull-right"
                    @click="changeStoreStatusTo('OUVERT')"
                >
                    OUVRIR
                </button>

                <button
                    v-else
                    class="btn btn-danger btn-open-close pull-right"
                    @click="changeStoreStatusTo('FERME')"
                >
                    FERMER
                </button>
            </div>
        </div>

        <Information class="information-wrapper" />

        <Contact class="contact-wrapper" />

        <ImageUploader class="image-uploader-wrapper" />
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Information from './Information';
import Contact from './Contact';
import ImageUploader from './ShopUploaderImage';

export default {
    name: 'ShopInformation',
    components: {
        Information,
        Contact,
        ImageUploader
    },
    computed: {
        ...mapState({
            Shop_status: state => state.DetailsShopModule.ShopStatus
        }),
        Shop_name: {
            get () {
                return this.$store.getters.getEnseingeName;
            }
        },
        Shop_ref: {
            get () {
                return this.$store.getters.getEnseingereference;
            }
        },
        button_Activation: {
            get () {
                return this.$store.getters.getEnseingerstatus;
            }
        }
    },
    mounted: function () {
        this.$store.dispatch('INITIALIZE_LIST_ABONNEMENT');
    },
    methods: {
        activation (value) {
            this.$store.commit('SET_STATUS_SHOP_DETAILS', value);
            this.$store.dispatch('SET_UPDATE_STATUS_SHOP_DETAILS');
        },
        changeStoreStatusTo (status) {
            this.$store.dispatch('SET_UPDATE_ENSEIGNE_STATUS', status);
        }
    }
};
</script>

<style lang="scss">
#ShopInformation {
    #Shop_Name {
        padding: 7px;

        .entreprise-info,
        .entreprise-open-close {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .entreprise-open-close {
            .btn-open-close {
                width: 75px;
                margin-right: 10px;
            }
        }
    }
}
</style>
