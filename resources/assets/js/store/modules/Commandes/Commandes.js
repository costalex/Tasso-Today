import Vue from 'vue';

const state = {
    commande: [],
    select_commande: '',
    select_status: '',
    notification: false
};

const getters = {
    getSelectCommande: state => state.select_commande,
    getCommande: state => state.commande
};

const mutations = {
    SET_COMMANDE: (state, commande) => {
        state.commande = commande;
    },
    SET_SELECT_COMMANDE: (state, value) => {
        state.select_commande = value;
    },
    SET_NOTIFICATION: (state, notification) => {
        state.notification = notification;
    }
};

const actions = {
    GET_COMMANDE: ({ state, commit, dispatch, rootState }) => {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        Vue.http.get(baseUrl + '/api/getCommandList')
            .then((response) => {
                if (state.commande.length < response.body.length) {
                    commit('SET_NOTIFICATION', true);
                }

                commit('SET_COMMANDE', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    UPDATE_COMMANDE_STATUT: ({ state, commit, dispatch, rootState }, value) => {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            statut: value.statut,
            num_commande: value.num_commande,
            annulation_commentaire: value.commentCancel
        };

        Vue.http.post(baseUrl + '/api/updateCommandeStatut', data)
            .then(() => {
                dispatch('GET_COMMANDE');

                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: 'Le status de la commande a été modifié'
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    DOWLOAD_CANCELED_REPPORT ({ state, commit, dispatch, rootState }, orderNum) {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            responseType: 'arraybuffer'
        };

        Vue.http.get(baseUrl + '/api/downloadJustifCmd/' + orderNum, data)
            .then((response) => {
                let blob = new Blob([response.data], { type: response.headers.get('content-type') });
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = 'Reçu-' + orderNum + '.pdf';
                link.click();
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    }
};

const CommandesModule = {
    state,
    getters,
    mutations,
    actions
};

export default CommandesModule;
