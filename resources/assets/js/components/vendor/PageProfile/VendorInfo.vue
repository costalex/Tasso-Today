<template>
    <div
        id="Enseigne_Details"
        class="col-md-12 col-sm-12 col-xs-12 no-padding"
    >
        <div class="panel panel-default col-md-12 col-sm-12 col-xs-12 no-padding">
            <div class="panel-heading col-md-12 col-sm-12 col-xs-12 no-padding">
                <div
                    class="col-md-6 col-sm-6 col-xs-6 no-padding"
                    align="left"
                    style="padding-left: 15px; padding-top: 2%;"
                >
                    <p>Details</p>
                </div>
                <div
                    class="col-md-6 col-sm-6 col-xs-6 no-padding"
                    align="right"
                >
                    <button
                        v-if="!modif_details"
                        class="btn btn-edit-infos-enseigne"
                        @click="ModifDetails"
                    >
                        Modifier
                    </button>

                    <button
                        v-else
                        class="btn btn-edit-infos-enseigne"
                        @click="SaveDetails"
                    >
                        Enregistrer
                    </button>
                </div>
            </div>

            <div class="panel-body col-md-12 col-sm-12 col-xs-12 no-padding">
                <div
                    id="informations_compte"
                    class="col-md-6 col-sm-6 col-xs-6 details-enseigne"
                >
                    <u>INFORMATION COMPTE</u>

                    <p
                        v-for="(information, index) in information_compte"
                        :key="index"
                    >
                        {{ information }} : {{ enseigne_information_compte[information] }}
                    </p>

                    <p
                        v-for="(information, index) in information_compte_modif"
                        :key="index"
                    >
                        {{ information }} :
                        <input
                            v-if="information !== 'Localisation'"
                            v-model="enseigne_information_compte[information]"
                            :disabled="!modif_details"
                            type="text"
                        >

                        <select
                            v-if="information === 'Localisation' && modif_details !== 0"
                            v-model="enseigne_information_compte[information]"
                        >
                            <option
                                v-for="option in Villes"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>

                        <input
                            v-if="information === 'Localisation' && !modif_details"
                            v-model="enseigne_information_compte[information].nom"
                            :disabled="!modif_details"
                            type="text"
                        >
                    </p>

                    <p v-if="!modif_details">
                        Email de connexion :  {{ EmailConnection }}
                    </p>

                    <p v-else>
                        Email de connexion :

                        <input
                            v-model="emailModif"
                            :disabled="!modif_details"
                        >
                    </p>
                </div>

                <div
                    id="informations_enseigne"
                    class="col-md-6 col-sm-6 col-xs-6 details-enseigne"
                >
                    <u>INFORMATION ENSEIGNE</u>

                    <p
                        v-for="(information, index) in information_enseigne"
                        :key="index"
                    >
                        {{ information }} :

                        <span v-if="information !== 'Secteur d\'activité'">
                            {{ enseinge_information_enseigne[information] }}
                        </span>

                        <span v-else>
                            {{ enseinge_information_enseigne[information].nom }}
                        </span>
                    </p>

                    <template v-if="information !== 'Description'">
                        <p
                            v-for="(information, index) in information_enseigne_modif"
                            :key="index"
                        >
                            {{ information }} :

                            <input
                                v-if="information !== 'Secteur d\'activité' && information !== 'Description'"
                                v-model="enseinge_information_enseigne[information]"
                                :disabled="!modif_details"
                                type="text"
                            >
                        </p>
                    </template>

                    <textarea
                        v-if="information !== 'Secteur d\'activité'"
                        v-model="enseinge_information_enseigne['Description']"
                        :disabled="!modif_details"
                        rows="2"
                        style="resize: none; width: 80%;"
                    />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'VendorInfo',
    data: () => {
        return {
            modif_details: false,
            information_compte: [
                'Référence',
                'Date de création',
                'Abonnement',
                'Date d\'abonnement'
            ],
            information_compte_modif: [
                'Courriel de facturation',
                'Adresse de facturation',
                'Ville',
                'Code postal'
            ],
            information_enseigne: [
                'Raison sociale',
                'SIRET',
                'Secteur d\'activité'
            ],
            information_enseigne_modif: [
                'Adresse',
                'Code postal',
                'Ville',
                'Description'
            ],
            emailModif: ''
        };
    },
    computed: {
        ...mapState({
            EmailConnection: state => state.GeneralModule.UserInfo.user.email
        }),
        enseigne_information_compte: {
            set (value) {
                this.$store.commit('SET_ENSEIGNE_INFORMATION_COMPTE', value);
            },
            get () {
                return this.$store.getters.getEnseingeInformationCompte;
            }
        },
        enseinge_information_enseigne: {
            set (value) {
                this.$store.commit('SET_ENSEIGNE_INFORMATION_ENSEIGNE', value);
            },
            get () {
                return this.$store.getters.getEnseingeInformationEnseigne;
            }
        },
        Villes: {
            get () {
                return this.$store.getters.getVilles;
            }
        },
        SecteurActivite: {
            get () {
                return this.$store.getters.getSecteurActivite;
            }
        }
    },
    methods: {
        ModifDetails () {
            this.modif_details = true;
            this.emailModif = this.EmailConnection;
        },
        SaveDetails () {
            this.modif_details = false;
            this.$store.dispatch('SET_UPDATE_ENSEIGNE_DETAILS');
            if (this.emailModif !== this.EmailConnection) {
                this.$store.dispatch('UPDATE_USER_CONNECTION', { email: this.emailModif });
            }
        }
    }
};
</script>
