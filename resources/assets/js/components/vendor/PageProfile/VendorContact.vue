<template>
    <div
        id="Enseigne_Contact"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding">
            <div class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding">
                <div
                    class="col-md-6 col-sm-6 col-xs-6 no-padding"
                    align="left"
                    style="padding-left: 15px; padding-top: 2%;"
                >
                    <p>Contact</p>
                </div>

                <div
                    class="col-md-6 col-sm-6 col-xs-6 no-padding"
                    align="right"
                >
                    <button
                        v-if="!modif_contact"
                        class="btn btn-edit-infos-enseigne"
                        @click="ModifContact"
                    >
                        Modifier
                    </button>

                    <button
                        v-else
                        class="btn btn-edit-infos-enseigne"
                        @click="SaveContact"
                    >
                        Enregistrer
                    </button>
                </div>
            </div>

            <div class="panel-body col-md-12 col-sm-12 col-xs-12">
                <div
                    id="contact_enseigne"
                    class="panel-body col-md-12 col-sm-12 col-xs-12 contact-enseigne"
                >
                    <p
                        v-for="(information, index) in information_contact"
                        :key="index"
                    >
                        {{ information }}:
                        <input
                            v-model="enseinge_information_contact[information]"
                            :disabled="!modif_contact"
                            type="text"
                        >
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'VendorContact',
    data: () => {
        return {
            modif_contact: false,

            information_contact: [
                'Nom',
                'Prenom',
                'Téléphone',
                'Courriel'
            ]
        };
    },
    computed: {
        enseinge_information_contact: {
            get () {
                return this.$store.getters.getEnseingeInformationContact;
            }
        }
    },
    methods: {
        ModifContact () {
            this.modif_contact = true;
        },
        SaveContact () {
            this.modif_contact = false;
            this.$store.dispatch('SET_UPDATE_CONTACT_DETAILS');
        }
    }
};
</script>
