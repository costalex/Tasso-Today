import Vue from 'vue';

const state = {
    select_ville: 0,
    select_secteur_activite: 0,
    select_statut: '',
    select_enseigne_name: '',
    select_type_demande: 'type de demande',
    list_enseignes: [],
    loadingListEnseignes: null
};

const getters = {
    getEnseignes: state => state.list_enseignes,
    loadingListEnseignes: state => state.loadingListEnseignes
};

const mutations = {
    SET_SELECT_VILLE: (state, value) => {
        state.select_ville = value;
    },
    SET_SELECT_SECTEUR_ACTIVITES: (state, value) => {
        state.select_secteur_activite = value;
    },
    SET_SELECT_STATUT: (state, value) => {
        state.select_statut = value;
    },
    SET_SELECT_ENSEIGNE_NAME: (state, value) => {
        state.select_enseigne_name = value;
    },
    SET_LIST_ENSEIGNES: (state, enseigneList) => {
        state.list_enseignes = enseigneList;
    },
    SET_LOADIND_LIST_ENSEIGNES (state, loadingListEnseignes) {
        state.loadingListEnseignes = loadingListEnseignes;
    },
    SET_RESET_MODULE: (state) => {
        state.select_ville = 0;
        state.select_secteur_activite = 0;
        state.select_statut = '';
        state.select_enseigne_name = '';
        state.select_type_demande = 'type de demande';
        state.list_enseignes = [];
    }
};

const actions = {
    INITIALIZE_ENSEIGNES: ({ state, commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;
        const apiToken = rootState.GeneralModule.TokenApi;
        const loadingStates = rootState.GeneralModule.loadingStates;

        commit('SET_LOADIND_LIST_ENSEIGNES', loadingStates.LOADING);

        const data = {
            ville_id: state.select_ville,
            nom_enseigne: state.select_enseigne_name,
            type_activite_id: state.select_secteur_activite,
            status: state.select_statut
        };

        Vue.http.post(`${baseUrl}/api/entrepriseList`, data, {
            headers: {
                'Authorization': apiToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            } })
            .then(response => {
                commit('SET_LIST_ENSEIGNES', response.body);
                commit('SET_LOADIND_LIST_ENSEIGNES', loadingStates.LOADED);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });

                commit('SET_LOADIND_LIST_ENSEIGNES', loadingStates.ERROR);
            });
    },
    INITIALIZE_ENSEIGNE_BY_FAMMILLE_ID: ({ commit, dispatch, rootState }, familleId) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;
        const loadingStates = rootState.GeneralModule.loadingStates;

        commit('SET_LOADIND_LIST_ENSEIGNES', loadingStates.LOADING);

        Vue.http.get(`${baseUrl}/api/getEntreprisesCategories/${familleId}`)
            .then((response) => {
                commit('SET_LIST_ENSEIGNES', response.body);
                commit('SET_LOADIND_LIST_ENSEIGNES', loadingStates.LOADED);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });

                commit('SET_LOADIND_LIST_ENSEIGNES', loadingStates.ERROR);
            });
    }
};

const EnseigneModule = {
    state,
    getters,
    mutations,
    actions
};

export default EnseigneModule;
