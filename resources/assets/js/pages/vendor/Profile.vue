<template>
    <div
        id="ProfileVendeur"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
    >
        <div
            id="Enseigne_Name"
            class="col-md-12 col-sm-12 col-xs-12 enseigne-activation"
        >
            <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                <p class="enseigne-name" >
                    {{ enseigne_name }}
                </p>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                <button
                    class="btn btn-danger pull-right"
                    @click="updateStatus('ARRET')"
                >
                    Fermeture Prolongée
                </button>
            </div>
        </div>

        <Information/>

        <Contact/>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import Information from '@/components/vendor/PageProfile/VendorInfo';
import Contact from '@/components/vendor/PageProfile/VendorContact';

export default {
    name: 'Profile',
    components: {
        Information,
        Contact
    },
    computed: {
        ...mapGetters([
            'notificationTypes'
        ]),
        enseigne_name: {
            get () {
                return this.$store.getters.getEnseingeName;
            }
        },
        enseigne_ref: {
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
    methods: {
        updateStatus (value) {
            this.$store.dispatch('SET_UPDATE_STATUS_SHOP', value)
                .then(() => {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.SUCCESS,
                        message: 'L\'entreprise est passée en fermeture prolongée'
                    });
                });
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_mixins';

.enseigne-activation {
    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
    @include prefix(border-radius, 5px, webkit moz ms o);
    border: 1px solid $grey;
    margin-bottom: 30px;
    margin-top: 15px;
    padding-top: 10px;
    padding-bottom: 10px;

    .enseigne-name {
        margin-top: 3px;
    }
}
</style>
