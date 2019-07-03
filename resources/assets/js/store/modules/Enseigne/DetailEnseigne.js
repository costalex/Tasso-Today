import Vue from 'vue';

const state = {
    enseigne_reference: 0,
    enseigne_name: '',
    enseigne_status: {},
    enseigne_information_compte: {
        'Référence': '',
        'Date de création': '',
        'Abonnement': '',
        'Date d\'abonnement': '',
        'Localisation': '',
        'Courriel de facturation': '',
        'Adresse de facturation': '',
        'Ville': '',
        'Code postal': '',
        'FacebookLink': '',
        'InstagramLink': '',
        'TwitterLink': '',
        'PinterestLink': ''
    },
    enseinge_information_enseigne: {
        'Raison sociale': '',
        'SIRET': '',
        'Adresse': '',
        'Code postal': '',
        'Ville': '',
        'Secteur d\'activité': '',
        'Description': ''
    },
    enseinge_information_contact: {
        'Nom': '',
        'Prenom': '',
        'Téléphone': '',
        'Courriel': ''
    },
    enseigne_produit_use: 0,
    enseigne_produit: [],
    enseigne_demande: [],
    BannerImage: '',
    ProfileImage: '',
    NewBannerImage: '',
    NewProfileImage: '',
    ShopStatus: ''
};

const getters = {
    getEnseingeInformationCompte: state => state.enseigne_information_compte,
    getEnseingeInformationEnseigne: state => state.enseinge_information_enseigne,
    getEnseingeInformationContact: state => state.enseinge_information_contact,
    getEnseigneProduit: state => state.enseigne_produit,
    getEnseingeName: state => state.enseigne_name,
    getEnseingereference: state => state.enseigne_reference,
    getEnseingerstatus: state => state.enseigne_status
};

const mutations = {
    SET_ENSEIGNE_ALL_INFORMATION: (state, response) => {
        state.enseigne_name = response.body.nom_enseigne;
        state.enseigne_reference = response.body.id;
        state.BannerImage = response.body.banniere;
        state.ProfileImage = response.body.path_file_logo_entreprise;

        state.enseigne_information_compte['Référence'] = response.body.id;
        state.enseigne_information_compte['Date de création'] = response.body.created_at;
        state.enseigne_information_compte['Abonnement'] = response.body.abonnement.nom;
        state.enseigne_information_compte['Date d\'abonnement'] = response.body.date_abonnement;
        state.enseigne_information_compte['Localisation'] = response.body.ville;
        state.enseigne_information_compte['Courriel de facturation'] = response.body.addresse_entreprise.email_fact;
        state.enseigne_information_compte['Adresse de facturation'] = response.body.addresse_entreprise.addresse_fact;
        state.enseigne_information_compte['Ville'] = response.body.addresse_entreprise.commune_fact;
        state.enseigne_information_compte['Code postal'] = response.body.addresse_entreprise.code_postal_fact;

        state.enseinge_information_enseigne['Raison sociale'] = response.body.type_entreprise.abreviation;
        state.enseinge_information_enseigne['SIRET'] = response.body.siret;
        state.enseinge_information_enseigne['Adresse'] = response.body.addresse_entreprise.addresse;
        state.enseinge_information_enseigne['Code postal'] = response.body.addresse_entreprise.code_postal;
        state.enseinge_information_enseigne['Ville'] = response.body.addresse_entreprise.commune;
        state.enseinge_information_enseigne['Secteur d\'activité'] = response.body.type_activite;
        state.enseinge_information_enseigne['Description'] = response.body.description;

        state.enseinge_information_contact['Nom'] = response.body.contact_entreprise.nom;
        state.enseinge_information_contact['Prenom'] = response.body.contact_entreprise.prenom;
        state.enseinge_information_contact['Téléphone'] = response.body.contact_entreprise.telephone;
        state.enseinge_information_contact['Courriel'] = response.body.contact_entreprise.email;

        state.enseigne_produit_use = response.body.nb_products_use;

        state.ShopStatus = response.body.status;

        if (response.body.reseaux_sociaux) {
            state.enseigne_information_compte['FacebookLink'] = response.body.reseaux_sociaux.facebook;
            state.enseigne_information_compte['InstagramLink'] = response.body.reseaux_sociaux.instagram;
            state.enseigne_information_compte['TwitterLink'] = response.body.reseaux_sociaux.twitter;
            state.enseigne_information_compte['PinterestLink'] = response.body.reseaux_sociaux.pinterest;
        }

        state.enseigne_status = response.body.user;
        state.enseigne_produit = Object.keys(response.body.liste_produits).map(function (e) {
            return response.body.liste_produits[e];
        });
    },
    SET_ENSEIGNE_PRODUIT_MODIF: (state, value) => {
        state.enseigne_produit = value;
    },
    SET_ENSEIGNE_REFERENCE: (state, value) => {
        state.enseigne_reference = value;
    },
    SET_ENSEIGNE_NAME: (state, value) => {
        state.enseigne_name = value;
    },
    SET_ENSEIGNE_INFORMATION_ENSEIGNE: (state, value) => {
        state.enseinge_information_enseigne = value;
    },
    SET_ENSEIGNE_INFORMATION_COMPTE: (state, value) => {
        state.enseigne_information_compte = value;
    },
    SET_STATUS_SHOP_DETAILS: (state, value) => {
        state.enseigne_status.status = value;
    },
    SET_BANNER_IMAGE: (state, value) => {
        state.BannerImage = value;
    },
    SET_PROFILE_IMAGE: (state, value) => {
        state.ProfileImage = value;
    },
    SET_NEW_BANNER_IMAGE: (state, value) => {
        state.NewBannerImage = value;
    },
    SET_NEW_PROFILE_IMAGE: (state, value) => {
        state.NewProfileImage = value;
    },
    SET_SHOP_STATUS: (state, value) => {
        state.ShopStatus = value;
    },
    SET_RESET_MODULE: (state) => {
        state.enseigne_reference = 0;
        state.enseigne_name = '';
        state.enseigne_status = {};

        state.enseigne_information_compte = {
            'Référence': '',
            'Date de création': '',
            'Abonnement': '',
            'Date d\'abonnement': '',
            'Localisation': '',
            'Courriel de facturation': '',
            'Adresse de facturation': '',
            'Ville': '',
            'Code postal': ''
        };

        state.enseinge_information_enseigne = {
            'Raison sociale': '',
            'SIRET': '',
            'Adresse': '',
            'Code postal': '',
            'Ville': '',
            'Secteur d\'activité': '',
            'Description': ''
        };

        state.enseinge_information_contact = {
            'Nom': '',
            'Prenom': '',
            'Téléphone': '',
            'Courriel': ''
        };

        state.enseigne_produit = [];
        state.enseigne_demande = [];
    }
};

const actions = {
    SET_ENSEIGNE_DETAILS: ({ state, commit, dispatch, rootState }) => {
        return new Promise((resolve, reject) => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            Vue.http.get(baseUrl + '/api/userInfos/' + state.enseigne_reference.toString())
                .then((response) => {
                    commit('SET_ENSEIGNE_ALL_INFORMATION', response);

                    if (response.body.user.user_type_id === 'Admin') {
                        commit('SET_TYPE_USER', 'Admin');
                    } else if (response.body.user.user_type_id === 'Entreprise') {
                        commit('SET_TYPE_USER', 'Vendeur');
                    } else if (response.body.user.user_type_id === 'Client') {
                        commit('SET_TYPE_USER', 'Client');
                    }

                    resolve();
                },
                response => {
                    dispatch('NOTIFY', {
                        type: rootState.GeneralModule.notificationTypes.ERROR,
                        message: response.body.message
                    });

                    reject(response);
                });
        });
    },
    SET_UPDATE_ENSEIGNE_DETAILS: ({ state, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            id: state.enseigne_reference,
            ville_id: state.enseigne_information_compte['Localisation'].id,
            email_fact: state.enseigne_information_compte['Courriel de facturation'],
            addresse_fact: state.enseigne_information_compte['Adresse de facturation'],
            commune_fact: state.enseigne_information_compte['Ville'],
            code_postal_fact: state.enseigne_information_compte['Code postal'],
            type_entreprise: state.enseinge_information_enseigne['Raison sociale'],
            abonnement: state.enseigne_information_compte['Abonnement'],
            addresse: state.enseinge_information_enseigne['Adresse'],
            code_postal: state.enseinge_information_enseigne['Code postal'],
            commune: state.enseinge_information_enseigne['Ville'],
            type_activite_id: state.enseinge_information_enseigne['Secteur d\'activité'].id,
            description: state.enseinge_information_enseigne['Description'],
            reseaux_sociaux: {
                facebook: state.enseigne_information_compte['FacebookLink'],
                instagram: state.enseigne_information_compte['InstagramLink'],
                twitter: state.enseigne_information_compte['TwitterLink'],
                pinterest: state.enseigne_information_compte['PinterestLink']
            }
        };

        Vue.http.put(baseUrl + '/api/userInfos/0', data)
            .then(() => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: 'Les détails de l\'entreprise ont été modifiés'
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_UPDATE_ENSEIGNE_STATUS: ({ state, dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            id: state.enseigne_reference,
            status: value
        };

        Vue.http.put(baseUrl + '/api/userInfos/0', data)
            .then(() => {
                dispatch('SET_ENSEIGNE_DETAILS')
                    .then(() => {
                        dispatch('NOTIFY', {
                            type: rootState.GeneralModule.notificationTypes.SUCCESS,
                            message: 'Le status de l\'entreprise a été modifé'
                        });
                    });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_UPDATE_CONTACT_DETAILS: ({ state, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            id: state.enseigne_reference,
            nom: state.enseinge_information_contact['Nom'],
            prenom: state.enseinge_information_contact['Prenom'],
            telephone: state.enseinge_information_contact['Téléphone'],
            email: state.enseinge_information_contact['Courriel']
        };

        Vue.http.put(baseUrl + '/api/userInfos/0', data)
            .then(() => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: 'Les détails du contact ont été modifiés'
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_UPDATE_STATUS_SHOP: ({ commit, dispatch, rootState }, value) => {
        return new Promise(resolve => {
            Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
            Vue.http.headers.common['Content-Type'] = 'application/json';
            Vue.http.headers.common['Accept'] = 'application/json';

            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = {
                status: value
            };

            Vue.http.put(baseUrl + '/api/userInfos/0', data)
                .then(() => {
                    commit('SET_SHOP_STATUS', value);

                    resolve();
                },
                response => {
                    dispatch('NOTIFY', {
                        type: rootState.GeneralModule.notificationTypes.ERROR,
                        message: response.body.message
                    });
                });
        });
    },
    SET_UPDATE_STATUS_SHOP_DETAILS: ({ state, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            id: state.enseigne_reference,
            status: state.enseigne_status.status
        };

        Vue.http.put(baseUrl + '/api/userStatusUpdate', data)
            .then(() => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: 'Le status du compte a été modifié'
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_ADD_PRODUIT_ENSEIGNE: ({ state, commit, dispatch, rootState }, value) => {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            id_entreprise: state.enseigne_reference,
            id_produit: value
        };

        Vue.http.post(baseUrl + '/api/addProduitsEntreprise', data)
            .then((response) => {
                dispatch('SET_TAB_ADD_PRODUIT');

                const enseigneProduit = Object.keys(response.body).map(function (e) {
                    return response.body[e];
                });

                commit('SET_ENSEIGNE_PRODUIT_MODIF', enseigneProduit);

                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: 'Le produit a été ajouté'
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_DELETE_PRODUIT_ENSEIGNE: ({ state, commit, dispatch, rootState }, value) => {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            id_entreprise: state.enseigne_reference,
            id_produit: value
        };

        Vue.http.post(baseUrl + '/api/deleteProduitsEntreprise', data)
            .then((response) => {
                dispatch('SET_TAB_ADD_PRODUIT');

                const enseigneProduit = Object.values(response.body);

                commit('SET_ENSEIGNE_PRODUIT_MODIF', enseigneProduit);

                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: 'Le produit a été supprimé'
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SEND_BANNER_AND_PROFILE_IMAGE: ({ state, commit, dispatch, rootState }) => {
        if (state.NewBannerImage !== '' || state.NewProfileImage !== '') {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = {
                id: state.enseigne_reference
            };

            if (state.NewBannerImage !== '') {
                data.banniere = state.NewBannerImage;
            }

            if (state.NewProfileImage !== '') {
                data.path_file_logo_entreprise = state.NewProfileImage;
            }

            Vue.http.put(baseUrl + '/api/userInfos/0', data)
                .then(() => {
                    if (state.NewBannerImage) {
                        commit('SET_BANNER_IMAGE', state.NewBannerImage);
                        commit('SET_PROFILE_IMAGE', state.NewProfileImage);
                    }

                    commit('SET_NEW_BANNER_IMAGE', '');
                    commit('SET_NEW_PROFILE_IMAGE', '');

                    dispatch('NOTIFY', {
                        type: rootState.GeneralModule.notificationTypes.SUCCESS,
                        message: 'Les images ont été modifiées'
                    });
                },
                response => {
                    dispatch('NOTIFY', {
                        type: rootState.GeneralModule.notificationTypes.ERROR,
                        message: response.body.message
                    });
                });
        }
    }
};

const DetailEnseigneModule = {
    state,
    getters,
    mutations,
    actions
};

export default DetailEnseigneModule;
