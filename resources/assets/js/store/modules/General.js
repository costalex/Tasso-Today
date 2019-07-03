import Vue from 'vue';
import env from '@/env';
import Cookies from 'js-cookie';
import LoadingStates from '@/class/LoadingStates';
import NotificationTypes from '@/class/NotificationTypes';

const BASE_URL = env.APP_URL;

const state = {
    BaseUrl: BASE_URL,
    TypeUser: 'Guest',
    TokenApi: '',
    UserInfo: {},
    Address: '',
    Address_detail: {},
    CookieAccepted: false,
    InscriptionStatu: false,
    loadingStates: new LoadingStates(),
    notificationTypes: new NotificationTypes()
};

const getters = {
    getTypeUser: state => state.TypeUser,
    getAddress: state => state.Address,
    loadingStates: state => state.loadingStates,
    notificationTypes: state => state.notificationTypes
};

const mutations = {
    SET_RESET_INFO: (state) => {
        state.BaseUrl = BASE_URL;
        state.TypeUser = 'Guest';
        state.TokenApi = '';
        state.UserInfo = {};
        state.Address = '';
    },
    SET_ADDRESS: (state, value) => {
        state.Address = value;
    },
    SET_ADDRESS_DETAIL: (state, value) => {
        state.Address_detail = value;
    },
    SET_USER: (state, value) => {
        state.UserInfo = value;
    },
    SET_RESET_MODULE: (state) => {
        state.BaseUrl = BASE_URL;
        state.TypeUser = '';
        state.TokenApi = '';
        state.UserInfo = {};
        state.Address = '';
    },
    SET_COOKI_ACCEPTED: (state, value) => {
        state.CookieAccepted = value;
    },
    SET_INSCRIPTION_STATU: (state, value) => {
        state.InscriptionStatu = value;
    },
    SET_TYPE_USER: (state, value) => {
        state.TypeUser = value;
    }
};

const actions = {
    CONNECT_USER: ({ state, commit, dispatch }, value) => {
        return new Promise((resolve, reject) => {
            const baseUrl = state.BaseUrl;

            Vue.http.post(baseUrl + '/api/login', value)
                .then(() => {
                    dispatch('GET_TYPE_USER')
                        .then(() => {
                            resolve();
                        })
                        .catch((response) => {
                            reject(response);
                        });
                },
                response => {
                    if (response.body.message) {
                        dispatch('NOTIFY', {
                            type: state.notificationTypes.ERROR,
                            message: response.body.message
                        });
                    } else {
                        dispatch('NOTIFY', {
                            type: state.notificationTypes.ERROR,
                            message: 'Email ou mot de passe invalide'
                        });
                    }

                    reject(response);
                });
        });
    },
    LOGOUT_USER: ({ state, commit, dispatch }) => {
        return new Promise((resolve, reject) => {
            const baseUrl = state.BaseUrl;

            Vue.http.get(baseUrl + '/api/logout')
                .then(() => {
                    commit('SET_RESET_MODULE');
                    Cookies.remove('API-TOKEN');

                    resolve();
                },
                response => {
                    dispatch('NOTIFY', {
                        type: state.notificationTypes.ERROR,
                        message: response.body.message
                    });

                    reject(response);
                });
        });
    },
    GET_TYPE_USER: ({ state, commit, dispatch }) => {
        return new Promise((resolve, reject) => {
            const baseUrl = state.BaseUrl;

            Vue.http.get(baseUrl + '/api/userInfos/0')
                .then(response => {
                    commit('SET_USER', response.body);

                    switch (response.body.user.user_type_id) {
                        case 'Admin':
                            commit('SET_TYPE_USER', 'Admin');

                            break;
                        case 'Entreprise':
                            commit('SET_TYPE_USER', 'Vendeur');
                            commit('SET_ENSEIGNE_ALL_INFORMATION', response);

                            break;
                        case 'Client':
                            commit('SET_TYPE_USER', 'Client');

                            if (response.body.addresses_livraison.length > 1) {
                                commit('SET_ADDRESS', response.body.addresses_livraison[0].addresse + ', ' +
                                    response.body.addresses_livraison[0].code_postal + ' ' +
                                    response.body.addresses_livraison[0].ville
                                );
                            }

                            commit('SET_PRENOM', response.body.prenom);
                            commit('SET_NOM', response.body.nom);
                            commit('SET_TELEPHONE', response.body.telephone);
                            commit('SET_EMAIL', response.body.user.email);

                            break;
                        default:
                            commit('SET_TYPE_USER', 'Guest');
                    }

                    if (response.body.user.user_type_id !== 'Guest') {
                        state.TokenApi = 'Bearer ' + response.body.user.api_token;
                        Vue.http.headers.common['Authorization'] = 'Bearer ' + response.body.user.api_token;
                        Vue.http.headers.common['Content-Type'] = 'application/json';
                        Vue.http.headers.common['Accept'] = 'application/json';
                    }

                    resolve();
                },
                response => {
                    dispatch('NOTIFY', {
                        type: state.notificationTypes.ERROR,
                        message: response.body.message
                    });

                    reject(response);
                });
        });
    },
    INSCRIPTION_CLIENT: ({ state, commit, dispatch }, value) => {
        return new Promise((resolve, reject) => {
            const baseUrl = state.BaseUrl;

            Vue.http.post(baseUrl + '/api/createUser', value)
                .then(response => {
                    commit('SET_INSCRIPTION_STATU', true);

                    dispatch('NOTIFY', {
                        type: state.notificationTypes.SUCCESS,
                        message: response.body.message
                    });

                    resolve();
                },
                response => {
                    commit('SET_INSCRIPTION_STATU', false);

                    dispatch('NOTIFY', {
                        type: state.notificationTypes.ERROR,
                        message: response.body.message
                    });

                    reject(response);
                });
        });
    },
    DELETE_ACCOUNT: ({ state, commit, dispatch }) => {
        return new Promise((resolve, reject) => {
            const baseUrl = state.BaseUrl;

            Vue.http.delete(baseUrl + '/api/userInfos')
                .then(() => {
                    commit('SET_RESET_MODULE');

                    resolve();
                },
                response => {
                    dispatch('NOTIFY', {
                        type: state.notificationTypes.ERROR,
                        message: response.body.message
                    });

                    reject(response);
                });
        });
    },
    SEND_LOST_PASSWORD: ({ state, commit, dispatch }, value) => {
        const baseUrl = state.BaseUrl;

        Vue.http.post(baseUrl + '/api/newPassword', value)
            .then(() => {
                dispatch('NOTIFY', {
                    type: state.notificationTypes.SUCCESS,
                    message: 'Une email viens de vous être envoyé.'
                });
            },
            response => {
                if (response.body.message) {
                    dispatch('NOTIFY', {
                        type: state.notificationTypes.ERROR,
                        message: response.body.message
                    });
                } else {
                    dispatch('NOTIFY', {
                        type: state.notificationTypes.ERROR,
                        message: 'Erreur email ou/et mot de passe invalide'
                    });
                }
            });
    },
    UPDATE_USER_CONNECTION: ({ state, commit, dispatch }, value) => {
        Vue.http.headers.common['Authorization'] = state.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = state.BaseUrl;

        Vue.http.put(baseUrl + '/api/userInfosUpdate', value)
            .then(() => {
                if (value.email) {
                    state.UserInfo.user.email = value.email;
                }
            },
            response => {
                dispatch('NOTIFY', {
                    type: state.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    UPDATE_USER: ({ state, commit, dispatch }) => {
        const baseUrl = state.BaseUrl;

        let data = {
            prenom: state.UserInfo.prenom,
            nom: state.UserInfo.nom,
            telephone: state.UserInfo.telephone
        };

        if (state.Address_detail.street_number) {
            data = {
                prenom: state.UserInfo.prenom,
                nom: state.UserInfo.nom,
                telephone: state.UserInfo.telephone,
                addresse: state.Address_detail.street_number + ' ' + state.Address_detail.route,
                code_postal: state.Address_detail.postal_code,
                ville: state.Address_detail.locality
            };
        }

        Vue.http.put(baseUrl + '/api/userInfos/0', data)
            .then(() => {
                if (state.Address_detail.street_number) {
                    commit('SET_ADDRESS_DETAIL', {});
                }
            },
            response => {
                dispatch('NOTIFY', {
                    type: state.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    }
};

const GeneralModule = {
    state,
    getters,
    mutations,
    actions
};

export default GeneralModule;
