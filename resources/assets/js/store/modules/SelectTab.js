import Vue from 'vue';

const state = {
    Families: [],
    Categories: [],
    Type: [],
    Mark: [],
    Unites: ['KG', 'L', 'UNITE'],
    Citys: ['Ville'],
    Activity_area: [],
    EntrepriseType: [],
    Status: [
        'Status',
        'VALIDATION_EN_ATTENTE',
        'ACTIVATION_EN_ATTENTE',
        'ACTIVE',
        'OUVERT',
        'FERME',
        'BAN'
    ],
    TypeRequest: [
        'Type de demande',
        'CREATION',
        'SUPPRESSION',
        'BUG',
        'PRODUITS',
        'PAIEMENT',
        'RDV',
        'EN_ATTENTE',
        'ACCEPTER',
        'REJETER'
    ],
    EventTab: ['Event', 'noel'],
    Abonnement: []
};

const getters = {
    getCategories: state => state.Categories,
    getType: state => state.Type,
    getFamilles: state => state.Families,
    getUnites: state => state.Unites,
    getVilles: state => state.Citys,
    getSecteurActivite: state => state.Activity_area,
    getEntrepriseType: state => state.EntrepriseType,
    getStatuts: state => state.Status,
    getMarques: state => state.Mark
};

const mutations = {
    SET_RESET_MARQUE: (state) => {
        state.marque.splice(0, state.Mark.length);
    },
    SET_RESET_MODULE: (state) => {
        state.produits_url = '/test';
        state.Families = [];
        state.Categories = [];
        state.Type = [];
        state.Mark = [];
        state.Unites = ['KG', 'L', 'UNITE'];
        state.Citys = ['Ville'];
        state.Activity_area = [];
        state.Status = [
            'Status',
            'VALIDATION_EN_ATTENTE',
            'ACTIVATION_EN_ATTENTE',
            'ACTIVE',
            'OUVERT',
            'FERME',
            'BAN'
        ];
        state.TypeRequest = [
            'Type de demande',
            'CREATION',
            'SUPPRESSION',
            'BUG',
            'PRODUITS',
            'PAIEMENT',
            'RDV',
            'EN_ATTENTE',
            'ACCEPTER',
            'REJETER'
        ];
        state.EventTab = ['Event', 'noel'];
    },
    SET_LIST_SECTEUR_ACTIVITE: (state, ActivityArea) => {
        state.Activity_area = ActivityArea;
    },
    SET_LIST_ENTREPRISE_TYPE: (state, EntrepriseType) => {
        state.EntrepriseType = EntrepriseType;
    },
    SET_LIST_VILLE: (state, value) => {
        state.Citys = value;
    },
    SET_LIST_CATEGORIE: (state, Categories) => {
        state.Categories = Categories;
    },
    SET_LIST_ABONNEMENT: (state, Abonnement) => {
        state.Abonnement = Abonnement;
    },
    SET_LIST_TYPE: (state, Type) => {
        state.Type = Type;
    },
    SET_LIST_FAMILLE: (state, Families) => {
        state.Families = Families;
    },
    SET_LIST_MARQUE: (state, Mark) => {
        state.Mark = Mark;
    }
};

const actions = {
    INITIALIZE_LIST_SECTEUR_ACTIVITE: ({ commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;
        const apiToken = rootState.GeneralModule.TokenApi;

        Vue.http.get(baseUrl + '/api/typeActiviteList', {
            headers: {
                'Authorization': apiToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            } })
            .then((response) => {
                commit('SET_LIST_SECTEUR_ACTIVITE', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    INITIALIZE_LIST_ENTREPRISE_TYPE: ({ commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;
        const apiToken = rootState.GeneralModule.TokenApi;

        Vue.http.get(baseUrl + '/api/typeEntrepriseList', {
            headers: {
                'Authorization': apiToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            } })
            .then((response) => {
                commit('SET_LIST_ENTREPRISE_TYPE', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    INITIALIZE_LIST_VILLE: ({ commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        Vue.http.get(baseUrl + '/api/villeList')
            .then((response) => {
                commit('SET_LIST_VILLE', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    INITIALIZE_LIST_CATEGORIE: ({ commit, dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        commit('SET_LIST_CATEGORIE', []);

        Vue.http.get(baseUrl + '/api/categorieList/' + value.toString())
            .then((response) => {
                commit('SET_LIST_CATEGORIE', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    INITIALIZE_LIST_ABONNEMENT: ({ commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        commit('SET_LIST_CATEGORIE', []);

        Vue.http.get(baseUrl + '/api/getAbonnementList')
            .then((response) => {
                commit('SET_LIST_ABONNEMENT', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    INITIALIZE_LIST_TYPE: ({ commit, dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;
        const apiToken = rootState.GeneralModule.TokenApi;

        Vue.http.get(baseUrl + '/api/typeList/' + value.toString(), {
            headers: {
                'Authorization': apiToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            } })
            .then((response) => {
                commit('SET_LIST_TYPE', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    INITIALIZE_LIST_FAMILLE: ({ commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        Vue.http.get(baseUrl + '/api/familleList')
            .then((response) => {
                commit('SET_LIST_FAMILLE', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    INITIALIZE_LIST_MARQUE: ({ commit, dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        Vue.http.get(baseUrl + '/api/marqueList/' + value.toString())
            .then((response) => {
                commit('SET_LIST_MARQUE', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    }
};

const SelectTabModule = {
    state,
    getters,
    mutations,
    actions
};

export default SelectTabModule;
