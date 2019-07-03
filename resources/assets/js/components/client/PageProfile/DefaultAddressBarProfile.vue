<template>
    <div
        id="AddressBar"
        class="col-md-10 col-sm-10 col-xs-12 input-group"
    >
        <vue-google-autocomplete
            id="map"
            ref="address"
            :enable-geolocation="true"
            :value="Address_select"
            classname="form-control"
            placeholder="Saisissez votre adresse"
            country="fr"
            @placechanged="getAddressData"
        />
    </div>
</template>

<script>
import VueGoogleAutocomplete from 'vue-google-autocomplete';

export default {
    name: 'AddressBar',
    components: { VueGoogleAutocomplete },
    data: () => {
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
        getAddressData (addressData, placeResultData) {
            this.$store.commit('SET_ADDRESS_DETAIL', addressData);
            this.$store.commit('SET_ADDRESS', placeResultData.formatted_address);
            this.address = placeResultData.formatted_address;
        }
    }
};
</script>
