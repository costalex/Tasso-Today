<template>
    <div
        class="input-group"
    >
        <vue-google-autocomplete
            :id="googleAutocompleteId"
            :enable-geolocation="false"
            :value="Address_select"
            classname="form-control"
            placeholder="Saisissez votre adresse de livraison"
            country="fr"
            @placechanged="getAddressData"
            @inputChange="setAddressData"
        />

        <div class="input-group-btn">
            <button
                class="btn btn-default"
                type="submit"
                @click="setCurrentPage"
            >
                <i class="glyphicon glyphicon-search" />
            </button>
        </div>
    </div>
</template>

<script>
import VueGoogleAutocomplete from 'vue-google-autocomplete';

export default {
    name: 'AddressBar',
    components: { VueGoogleAutocomplete },
    props: {
        googleAutocompleteId: { type: String, required: true }
    },
    data () {
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
    methods: {
        setAddressData (value) {
            this.$store.commit('SET_ADDRESS', value.newVal);
            this.address = value.newVal;
        },
        getAddressData (addressData, placeResultData) {
            this.$store.commit('SET_ADDRESS', placeResultData.formatted_address);
            this.address = placeResultData.formatted_address;
        },
        setCurrentPage () {
            if (this.Address_select) {
                this.$router.push({ name: 'familliesList' });
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.input-group {
    max-width: 550px;
    margin: 25px auto 0 auto;

    input {
        height: 50px;
        border: none;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.18);
    }

    .input-group-btn {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.18);
        border-bottom-right-radius: 4px;
        border-top-right-radius: 4px;

        button {
            height: 50px;
            width: 50px;
            border: none;
            display: flex;
            position: relative;
            justify-content: center;
            background-color: #5DD39E;

            &:hover {
                background-color: #1FA066;
            }

            i {
                color: #FFF;
                font-size: 18px;
            }
        }
    }
}
</style>
