<template>
    <div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal"
                    >
                        <img src="/storage/bobby_images/marketplace-client/icon-cross.svg">
                    </button>
                </div>

                <h2>À chaque adresse de nouveaux commerces</h2>

                <div class="input-group address-bar">
                    <vue-google-autocomplete
                        id="map"
                        ref="address"
                        :enable-geolocation="false"
                        :value="Address_select"
                        classname="form-control"
                        placeholder="Saisissez une adresse"
                        country="fr"
                        onfocus="this.value = ''"
                        @placechanged="getAddressData"
                        @inputChange="setAddressData"
                    />

                    <div class="input-group-btn">
                        <button
                            class="btn btn-default"
                            type="submit"
                            data-dismiss="modal"
                        >
                            <i class="glyphicon glyphicon-search" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueGoogleAutocomplete from 'vue-google-autocomplete';

export default {
    name: 'AddressBar',
    components: { VueGoogleAutocomplete },
    data: function () {
        return {
            address: ''
        };
    },
    computed: {
        Address_select: {
            get () {
                return this.$store.getters.getAddress;
            }
        }
    },
    mounted () {
        this.$refs.address.focus();
    },
    methods: {
        setAddressData: function (valueInput) {
            this.$store.commit('SET_ADDRESS', valueInput.newVal);
            this.address = valueInput.newVal;
        },
        getAddressData: function (addressData, placeResultData) {
            this.$store.commit('SET_ADDRESS', placeResultData.formatted_address);
            this.address = placeResultData.formatted_address;
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';

.address-bar {
    position: relative;
    width: 80%;
    margin: 0 auto 50px;
    padding: 0;
    border-radius: 4px;
    box-shadow: 0 1px 2px rgba(0,0,0,.5) ;

    .input-group-btn {
        width: 40px;
        height: 40px;

        .btn-default {
            height: 40px;
            width: 100%;
            background-color: $blue;
            border: none;
            margin: 0;

            .glyphicon {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                color: $white !important;
            }
        }
    }

    .form-control {
        border: none;
        height: 40px;
    }
}

@media only screen and (max-width: 600px) {
    .address-bar {
        width: 100%;
    }
}
</style>
