import Vue from 'vue';

const state = {
    Address: '',
    Information_complementaire: '',
    Prenom: '',
    Nom: '',
    Telephone: '',
    Email: '',
    Date_livraison: '',
    Date_livraison_end: '',
    bill: {},
    ref_bill: '',
    livraison: true,
    General_information: {},

    list_card: {},
    aff_card: false,
    send_token: true,
    pay_statu: 'finish'
};

const getters = {
    getLivraiosnMode: state => state.livraison,
    getDateLivraison: state => state.Date_livraison,
    getEndDateLivraison: state => state.Date_livraison_end,
    getAddressLivraison: state => state.Address,
    getInformationcomplementaire: state => state.Information_complementaire,
    getprenom: state => state.Prenom,
    getnom: state => state.Nom,
    gettelephone: state => state.Telephone,
    getemail: state => state.Email
};

const mutations = {
    SET_PAYMENT_STATU (state, value) {
        state.pay_statu = value;
    },
    SET_DATE_LIVRAISON (state, value) {
        const verifDate = new Date(value);

        if (verifDate.getHours() === 18) {
            verifDate.setMinutes(0);
            verifDate.setSeconds(0);
            verifDate.setMilliseconds(0);
        }

        const endDate = new Date(value);
        endDate.setHours(verifDate.getHours() + 1);
        state.Date_livraison_end = new Date(endDate);
        state.Date_livraison = new Date(verifDate);
    },
    SET_REF_BILL (state, value) {
        state.ref_bill = value;
    },
    SET_ADDRESS (state, value) {
        state.Address = value;
    },
    SET_INFORMATION_COMPLEMENTAIRE (state, value) {
        state.Information_complementaire = value;
    },
    SET_PRENOM (state, value) {
        if (!state.Prenom) {
            state.Prenom = value;
        }
    },
    SET_NOM (state, value) {
        if (!state.Nom) {
            state.Nom = value;
        }
    },
    SET_TELEPHONE (state, value) {
        if (!state.Telephone) {
            state.Telephone = value;
        }
    },
    SET_EMAIL (state, value) {
        if (!state.Email) {
            state.Email = value;
        }
    },
    SET_SEND_TOKEN (state, value) {
        state.send_token = value;
    },
    SET_RESET_MODULE: (state) => {
        state.Address = '';
        state.Information_complementaire = '';
        state.Prenom = '';
        state.Nom = '';
        state.Telephone = '';
        state.Email = '';
        state.Date_livraison = '';
        state.Date_livraison_end = '';
        state.bill = {};
        state.ref_bill = '';
        state.livraison = true;
        state.General_information = {};
        state.pay_statu = 'finish';

        state.list_card = {};
        state.aff_card = false;
        state.send_token = true;
    },
    SET_GENERAL_INFORMATIONS: (state, generalInformation) => {
        state.General_information = generalInformation;
    },
    SET_AFF_CARD: (state, affCard) => {
        state.aff_card = affCard;
    },
    SET_LIST_CARD: (state, listCard) => {
        state.list_card = listCard;
    },
    SET_MODE_LIVRAISON (state, value) {
        state.livraison = value;
    }
};

const actions = {
    GET_GENERAL_INFORMATIONS: ({ state, commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            addresse_livraison_client: state.Address,
            information_complémentaire: state.Information_complementaire,
            prenom: state.Prenom,
            nom: state.Nom,
            telephone: state.Telephone,
            email: state.Email,
            livraison: state.livraison,
            date_livraison: state.Date_livraison,
            paniers: rootState.CartModule.panier_general
        };

        Vue.http.post(baseUrl + '/api/updatePanierInformations', data)
            .then((response) => {
                commit('SET_GENERAL_INFORMATIONS', response.body.panier);

                if (response.body.modification) {
                    const panierSave = [];

                    if (response.body.panier.entreprises) {
                        const paniers = rootState.CartModule.panier_general;

                        for (let i = 0; i < paniers.length; i++) {
                            const panier = paniers[i];

                            if (!response.body.panier.entreprises[panier.shop.name]) {
                                panierSave.push(panier);
                            }
                        }
                    }

                    commit('SET_GENERAL_PANIER_CLIENT', panierSave);
                    dispatch('SET_GENERAL_INFORMATIONS');
                }
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SEND_PANIER ({ state, commit, dispatch, rootState }, panier) {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        commit('SET_PAYMENT_STATU', 'start');

        const data = {
            addresse_livraison_client: state.Address,
            information_complémentaire: state.Information_complementaire,
            prenom: state.Prenom,
            nom: state.Nom,
            telephone: state.Telephone,
            email: state.Email,
            livraison: state.livraison,
            date_livraison: state.Date_livraison,
            paniers: panier
        };

        const baseUrl = rootState.GeneralModule.BaseUrl;

        Vue.http.post(baseUrl + '/api/createFacture', data)
            .then((response) => {
                commit('SET_REF_BILL', response.body);

                dispatch('SEND_CHECKOUT');
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    DOWLOAD_BILL ({ state, commit, dispatch, rootState }) {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            responseType: 'arraybuffer'
        };

        Vue.http.get(baseUrl + '/api/downloadFacture/' + state.ref_bill, data)
            .then((response) => {
                let blob = new Blob([response.data], { type: response.headers.get('content-type') });
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'facture-' + state.ref_bill + '.pdf';
                link.click();
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SEND_TOKEN_PAYMENT ({ state, commit, dispatch, rootState }, stripetoken) {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        commit('SET_AFF_CARD', false);

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            stripeToken: stripetoken
        };

        Vue.http.post(baseUrl + '/api/createCustomerAccount', data)
            .then(() => {
                commit('SET_AFF_CARD', true);
                commit('SET_SEND_TOKEN', true);
                dispatch('GET_LIST_CARD_PAYMENT');
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    DELETE_CARD_PAYMENT ({ state, commit, dispatch, rootState }, card) {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        commit('SET_AFF_CARD', false);

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            card: card
        };

        Vue.http.post(baseUrl + '/api/deleteCard', data)
            .then(() => {
                commit('SET_AFF_CARD', true);
                dispatch('GET_LIST_CARD_PAYMENT');
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    GET_LIST_CARD_PAYMENT ({ state, commit, rootState }) {
        commit('SET_AFF_CARD', false);
        commit('SET_LIST_CARD', {});

        const userInfo = rootState.GeneralModule.UserInfo;

        if (userInfo.user.card_brand) {
            Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
            Vue.http.headers.common['Content-Type'] = 'application/json';
            Vue.http.headers.common['Accept'] = 'application/json';

            const baseUrl = rootState.GeneralModule.BaseUrl;

            Vue.http.get(baseUrl + '/api/getPaiementCardList')
                .then(response => {
                    commit('SET_LIST_CARD', response.body);
                    commit('SET_AFF_CARD', true);
                },
                response => {
                    commit('SET_AFF_CARD', true);
                });
        } else {
            commit('SET_AFF_CARD', true);
        }
    },
    SEND_CHECKOUT ({ state, commit, dispatch, rootState }) {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            num_fact: state.ref_bill.num_cmd
        };

        Vue.http.post(baseUrl + '/api/pay', data)
            .then(() => {
                localStorage.clear();
                commit('SET_PAYMENT_STATU', 'sucess');
                commit('SET_GENERAL_PANIER_CLIENT', []);
                dispatch('GET_GENERAL_INFORMATIONS');
            },
            response => {
                commit('SET_PAYMENT_STATU', 'fail');

                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    UPDATE_DEFAULT_CARD ({ state, commit, dispatch, rootState }, value) {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        state.aff_card = false;

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            default_source: value.id
        };

        Vue.http.post(baseUrl + '/api/changeDefaultCard', data)
            .then(() => {
                dispatch('GET_LIST_CARD_PAYMENT');
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    }
};

const CommandeClientModule = {
    state,
    getters,
    mutations,
    actions
};

export default CommandeClientModule;
