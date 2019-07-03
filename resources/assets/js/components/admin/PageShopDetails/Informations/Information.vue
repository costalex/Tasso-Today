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
                        {{ information }} :
                        <span style="font-weight: normal">
                            {{ enseigne_information_compte[information] }}
                        </span>
                    </p>

                    <p
                        v-for="(information, index) in information_compte_modif"
                        :key="index"
                    >
                        {{ information }} :
                        <input
                            v-if="information !== 'Localisation' && information !== 'Abonnement'"
                            v-model="enseigne_information_compte[information]"
                            :disabled="!modif_details"
                            type="text"
                        >

                        <select
                            v-else-if="information === 'Localisation' && modif_details"
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
                            v-else-if="information === 'Localisation' && !modif_details"
                            v-model="enseigne_information_compte[information].nom"
                            :disabled="!modif_details"
                            type="text"
                        >

                        <select
                            v-else-if="information === 'Abonnement' && modif_details"
                            v-model="enseigne_information_compte[information]"
                        >
                            <option
                                v-for="option in ListAbonnement"
                                :key="option.id"
                                :value="option.nom"
                            >
                                {{ option.nom }}
                            </option>
                        </select>

                        <input
                            v-else-if="information === 'Abonnement' && !modif_details"
                            v-model="enseigne_information_compte[information]"
                            :disabled="!modif_details"
                            type="text"
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
                        <span style="font-weight: normal">
                            {{ enseinge_information_enseigne[information] }}
                        </span>
                    </p>

                    <p
                        v-for="(information, index) in information_enseigne_modif"
                        :key="index"
                    >
                        {{ information }} :
                        <input
                            v-if="information !== 'Secteur d\'activité' && information !== 'Raison sociale' && information !== 'Description'"
                            v-model="enseinge_information_enseigne[information]"
                            :disabled="!modif_details"
                            type="text"
                        >

                        <select
                            v-else-if="information === 'Secteur d\'activité' && modif_details"
                            v-model="enseinge_information_enseigne[information]"
                        >
                            <option
                                v-for="option in SecteurActivite"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>

                        <input
                            v-else-if="information === 'Secteur d\'activité' && !modif_details"
                            v-model="enseinge_information_enseigne[information].nom"
                            :disabled="!modif_details"
                            type="text"
                        >

                        <select
                            v-else-if="information === 'Raison sociale' && modif_details"
                            v-model="enseinge_information_enseigne[information]"
                        >
                            <option
                                v-for="option in ListType"
                                :key="option.id"
                                :value="option.abreviation"
                            >
                                {{ option.abreviation }}
                            </option>
                        </select>

                        <input
                            v-else-if="information === 'Raison sociale' && !modif_details"
                            v-model="enseinge_information_enseigne[information]"
                            :disabled="!modif_details"
                            type="text"
                        >

                        <textarea
                            v-else-if="information === 'Description'"
                            v-model="enseinge_information_enseigne['Description']"
                            :disabled="!modif_details"
                            rows="2"
                            style="resize: none; width: 80%;"
                        />
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'Information',
    data: () => {
        return {
            modif_details: false,
            information_compte: [
                'Référence',
                'Date de création',
                'Date d\'abonnement'
            ],
            information_compte_modif: [
                'Abonnement',
                'Localisation',
                'Courriel de facturation',
                'Adresse de facturation',
                'Ville',
                'Code postal'
            ],
            information_enseigne: [
                'SIRET'
            ],
            information_enseigne_modif: [
                'Raison sociale',
                'Adresse',
                'Code postal',
                'Ville',
                'Secteur d\'activité',
                'Description'
            ]
        };
    },
    computed: {
        ...mapState({
            ListAbonnement: state => state.SelectTabModule.Abonnement,
            ListType: state => state.SelectTabModule.EntrepriseType
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
        },
        SaveDetails () {
            this.modif_details = false;
            this.$store.dispatch('SET_UPDATE_ENSEIGNE_DETAILS');
        }
    }
};
</script>
