<template>
    <div id="ShopManageBody">
        <div id="Switch">
            <button @click="$router.push({ name: 'ShopListBody' })">
                Retour à la liste des enseignes
            </button>
        </div>

        <div
            class="row"
            align="center"
        >
            <div
                class="registration"
                align="center"
            >
                <h1>Inscription entreprise</h1>

                <div class="form-group">
                    <p>Nom du contact :</p>
                    <input
                        id="contact-firstname"
                        v-model="info_user.nom_contact"
                        class="custom-input-top"
                        placeholder="Nom contact"
                    >

                    <p>Prenom du contact :</p>
                    <input
                        id="contact-lastname"
                        v-model="info_user.prenom_contact"
                        class="custom-input-top"
                        placeholder="Prenom contact"
                    >

                    <p>Telephone du contact :</p>
                    <input
                        id="contact-phone"
                        v-model="info_user.telephone_contact"
                        class="custom-input-top"
                        placeholder="Telephone contact"
                    >

                    <p>E-mail du contact :</p>
                    <input
                        id="contact-email"
                        v-model="info_user.email_contact"
                        class="custom-input-top"
                        placeholder="Email contact"
                    >

                    <p>Adresse du contact :</p>
                    <vue-google-autocomplete
                        id="contact-address"
                        ref="contact-address"
                        :enable-geolocation="false"
                        :value="info_user.addresse_contact_entreprise"
                        classname="form-control"
                        placeholder="Saisissez l'adresse du contact"
                        country="fr"
                        onfocus="this.value = ''"
                        @placechanged="getContactAddressData"
                        @inputChange="setContactAddressData"
                    />
                </div>

                <div class="form-group">
                    <p>Nom e-boutique (affiché aux clients) :</p>
                    <input
                        id="enseigne-name"
                        v-model="info_user.nom_enseigne"
                        class="custom-input-top"
                        placeholder="Nom e-boutique"
                    >

                    <div>
                        <p>Status entreprise*:</p>
                        <select
                            v-model="select_entreprise_type"
                            class="filter-picker"
                        >
                            <option
                                v-for="option in EntrepriseType"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.abreviation }}
                            </option>
                        </select>
                    </div>

                    <div>
                        <p>Secteur activitée*:</p>
                        <select
                            v-model="select_secteur_activite"
                            class="filter-picker"
                        >
                            <option
                                v-for="option in SecteurActivite"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <p>Zone de livraison*: </p>
                        <select
                            v-model="select_entreprise_city"
                            class="filter-picker"
                        >
                            <option
                                v-for="option in Citys"
                                :key="option.id"
                                :value="option"
                            >
                                {{ option.nom }}
                            </option>
                        </select>
                    </div>

                    <p>Nom de facturation de l'entreprise :</p>
                    <input
                        id="enseigne-nom-facturation"
                        v-model="info_user.nom_fact_entreprise"
                        class="custom-input-middle"
                        placeholder="Nom entreprise facturation"
                    >

                    <p>Numéro de SIRET de l'entreprise (Attention: non modifiable) :</p>
                    <input
                        id="enseigne-siret"
                        v-model="info_user.siret"
                        class="custom-input-middle"
                        placeholder="SIRET*"
                    >

                    <p>Adresse de facturation de l'entreprise :</p>
                    <vue-google-autocomplete
                        id="entreprise-address"
                        ref="entreprise-address"
                        :enable-geolocation="false"
                        :value="info_user.addresse_fact_contact_entreprise"
                        classname="form-control"
                        placeholder="Saisissez l'adresse de l'entreprise"
                        country="fr"
                        onfocus="this.value = ''"
                        @placechanged="getEntrepriseAddressData"
                        @inputChange="setEntrepriseAddressData"
                    />

                    <p>E-mail de facturation de l'entrperise :</p>
                    <input
                        id="enseigne-email"
                        v-model="info_user.email_fact_contact_entreprise"
                        class="custom-input-bottom"
                        placeholder="Email de facturation de l'entreprise"
                    >
                </div>

                <p>Description de l'entreprise :</p>
                <div class="form-group">
                    <textarea
                        v-model="info_user.description"
                        class="textarea-vendor-registration"
                        rows="3"
                        name="description"
                        placeholder="Description de l'entreprise"
                    />
                </div>

                <div class="form-group">
                    <p>E-mail de connexion à l'espace vendeur :</p>
                    <input
                        id="mail"
                        v-model="info_user.email"
                        class="custom-input-middle"
                        placeholder="Email de connexion*"
                    >

                    <p>Mot de passe de connexion à l'espace vendeur :</p>
                    <input
                        id="password"
                        v-model="info_user.password"
                        class="custom-input-middle"
                        placeholder="Mot de passe*"
                        type="password"
                    >

                    <p>Confirmation du mot de passe :</p>
                    <input
                        id="confirm-password"
                        v-model="confirm_password"
                        class="custom-input-bottom"
                        placeholder="Confirmer mot de passe*"
                        type="password"
                    >
                </div>

                <div class="form-group">
                    <button
                        class="btn registration-button"
                        @click="Inscription"
                    >
                        INSCRIRE
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueGoogleAutocomplete from 'vue-google-autocomplete';
import NotificationTypes from '@/class/NotificationTypes';

export default {
    name: 'VendorRegistration',
    components: { VueGoogleAutocomplete },
    data: () => {
        return {
            info_user: {
                // connexion
                email: '',
                password: '',
                // Description
                type_activite: '',
                type_entreprise: '',
                nom_enseigne: '',
                description: '',
                siret: '',
                ville: '',
                // Facturation
                nom_fact_entreprise: '',
                code_postal_fact_entreprise: '',
                commune_fact_entreprise: '',
                email_fact_contact_entreprise: '',
                addresse_fact_contact_entreprise: '',
                // Contact
                prenom_contact: '',
                telephone_contact: '',
                nom_contact: '',
                email_contact: '',
                addresse_contact_entreprise: '',
                code_postal_contact_entreprise: '',
                commune_contact_entreprise: ''
            },
            cgu: false,
            confirm_password: '',
            select_secteur_activite: { id: 0, nom: 'Secteurs d\'activites' },
            select_entreprise_type: { id: 0, nom: 'Type d\'entreprise' },
            select_entreprise_city: { id: 0, nom: 'Villes' },
            notificationTypes: new NotificationTypes()
        };
    },
    computed: {
        SecteurActivite: {
            get () {
                return this.$store.getters.getSecteurActivite;
            }
        },
        EntrepriseType: {
            get () {
                return this.$store.getters.getEntrepriseType;
            }
        },
        Citys: {
            get () {
                return this.$store.getters.getVilles;
            }
        }
    },
    watch: {
        select_secteur_activite: function (val) {
            this.info_user.type_activite = val.nom;
        },
        select_entreprise_type: function (val) {
            this.info_user.type_entreprise = val.abreviation;
        },
        select_entreprise_city: function (val) {
            this.info_user.ville = val.nom;
        }
    },
    mounted: function () {
        this.$store.dispatch('INITIALIZE_LIST_SECTEUR_ACTIVITE');
        this.$store.dispatch('INITIALIZE_LIST_ENTREPRISE_TYPE');
        this.$store.dispatch('INITIALIZE_LIST_VILLE');
    },
    methods: {
        Inscription () {
            if (this.info_user.password === this.confirm_password) {
                if (this.info_user.email &&
                    this.info_user.type_activite &&
                    this.info_user.type_entreprise &&
                    this.info_user.nom_enseigne &&
                    this.info_user.siret &&
                    this.info_user.ville
                ) {
                    this.$store.dispatch('INSCRIPTION_CLIENT', this.info_user)
                        .then(() => {
                            this.$router.push({ name: 'ShopListBody' });
                        });
                } else {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.ERROR,
                        message: 'Vous devez remplir tous les champs obligatoires'
                    });
                }
            } else {
                this.$store.dispatch('NOTIFY', {
                    type: this.notificationTypes.ERROR,
                    message: 'Les mots de passe ne correspondent pas'
                });
            }
        },
        getContactAddressData: function (value) {
            this.info_user.code_postal_contact_entreprise = value.postal_code;
            this.info_user.commune_contact_entreprise = value.locality;
        },
        setContactAddressData: function (addressData) {
            this.info_user.addresse_contact_entreprise = addressData.newVal;
        },
        getEntrepriseAddressData: function (value) {
            this.info_user.addresse_fact_contact_entreprise = value.newVal;
            this.info_user.code_postal_fact_entreprise = value.postal_code;
            this.info_user.commune_fact_entreprise = value.locality;
        },
        setEntrepriseAddressData: function (addressData) {
            this.info_user.addresse_fact_contact_entreprise = addressData.newVal;
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.textarea-vendor-registration {
    @include font-size(1.4);
    background-color: $light-grey;
    border-color: $grey;
    border-radius: 5px;
    font-weight: 300;
    width: 270px;
    height: 100%;
    padding-left: 10px;
    resize: none;
}

#enseigne-name {
    @include font-size(1.8);
}
</style>
