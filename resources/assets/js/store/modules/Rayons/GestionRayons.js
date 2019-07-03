import Vue from 'vue';

const state = {
    list_rayon: []
};

const getters = {
    getRayonsDetails: (state) => state.list_rayon
};

const mutations = {
    SET_LIST_RAYON: (state, value) => {
        state.list_rayon = value;
    },
    ADD_RAYON_TO_LIST_RAYON: (state, rayon) => {
        state.list_rayon.push(rayon);
    },
    SET_RESET_MODULE: (state) => {
        state.list_rayon = [];
    }
};

const actions = {
    SET_RAYONS_ORDER: ({ commit, dispatch }, value) => {
        return new Promise((resolve, reject) => {
            commit('SET_LIST_RAYON', value);
            dispatch('SEND_RAYONS_ORDER')
                .then(() => {
                    resolve();
                })
                .catch((response) => {
                    reject(response);
                });
        });
    },
    SET_RAYONS_VENDEUR: ({ state, commit, dispatch, rootState }) => {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        Vue.http.get(baseUrl + '/api/getEtageresList')
            .then((response) => {
                commit('SET_LIST_RAYON', []);

                const values = Object.keys(response.body).map(function (e) {
                    return response.body[e];
                });

                if (values.length !== 0) {
                    for (let rayonIndex in response.body) {
                        const rayon = {
                            sous_rayons: []
                        };

                        for (let subrayonIndex in response.body[rayonIndex]) {
                            if (subrayonIndex === 'rayon_nom') {
                                rayon.rayon_nom = response.body[rayonIndex].rayon_nom;
                            } else if (subrayonIndex === 'rayon_id') {
                                rayon.rayon_id = response.body[rayonIndex].rayon_id;
                            } else {
                                const sousRayon = {
                                    etageres: []
                                };

                                for (let etagereIndex in response.body[rayonIndex][subrayonIndex]) {
                                    if (etagereIndex === 'sous_rayon_nom') {
                                        sousRayon.sous_rayon_nom = response.body[rayonIndex][subrayonIndex].sous_rayon_nom;
                                    } else if (etagereIndex === 'sous_rayon_id') {
                                        sousRayon.sous_rayon_id = response.body[rayonIndex][subrayonIndex].sous_rayon_id;
                                    } else {
                                        const etagere = {};

                                        etagere.etagere_id = response.body[rayonIndex][subrayonIndex][etagereIndex].etagere_id;
                                        etagere.etagere_nom = response.body[rayonIndex][subrayonIndex][etagereIndex].etagere_nom;
                                        etagere.produit = response.body[rayonIndex][subrayonIndex][etagereIndex].list_produits;
                                        etagere.type = response.body[rayonIndex][subrayonIndex][etagereIndex].type;
                                        etagere.fondEcran = response.body[rayonIndex][subrayonIndex][etagereIndex].fondEcran;

                                        sousRayon.etageres.push(etagere);
                                    }
                                }

                                rayon.sous_rayons.push(sousRayon);
                            }
                        }

                        commit('ADD_RAYON_TO_LIST_RAYON', rayon);
                    }
                }
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SEND_RAYONS_ORDER: ({ state, dispatch, rootState }) => {
        return new Promise((resolve, reject) => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = { shop_order: state.list_rayon };

            Vue.http.put(baseUrl + '/api/updateShopOrder', data)
                .then(() => {
                    dispatch('SET_RAYONS_VENDEUR');

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
    SET_CREATE_RAYON: ({ state, dispatch, rootState }, value) => {
        return new Promise(resolve => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = {
                nom: 'Nouveau Rayon'
            };

            Vue.http.post(baseUrl + '/api/createRayon', data)
                .then((response) => {
                    state.list_rayon.push({
                        rayon_id: response.body.id,
                        rayon_nom: 'Nouveau Rayon',
                        sous_rayons: []
                    });

                    dispatch('SET_CREATE_SOUS_RAYON', {
                        id: value.id,
                        rayon_id: response.body.id,
                        index: (state.list_rayon.length - 1)
                    })
                        .then(() => {
                            resolve();
                        });
                },
                response => {
                    dispatch('NOTIFY', {
                        type: rootState.GeneralModule.notificationTypes.ERROR,
                        message: response.body.message
                    });
                });
        });
    },
    SET_CREATE_SOUS_RAYON: ({ state, dispatch, rootState }, value) => {
        return new Promise(resolve => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = {
                rayon_id: value.rayon_id,
                nom: 'Nouveau sous-rayon'
            };

            Vue.http.post(baseUrl + '/api/createSousRayon', data)
                .then((response) => {
                    state.list_rayon[value.id].sous_rayons.push({
                        sous_rayon_id: response.body.id,
                        sous_rayon_nom: 'Nouveau sous-rayon',
                        etageres: []
                    });

                    dispatch('SET_CREATE_ETAGERE', {
                        id_rayon: value.rayon_id,
                        id_subrayon: response.body.id,
                        index_rayon: value.id,
                        index: (state.list_rayon[value.id].sous_rayons.length - 1)
                    })
                        .then(() => {
                            resolve();
                        });
                },
                response => {
                    dispatch('NOTIFY', {
                        type: rootState.GeneralModule.notificationTypes.ERROR,
                        message: response.body.message
                    });
                });
        });
    },
    SET_CREATE_ETAGERE: ({ state, commit, dispatch, rootState }, value) => {
        return new Promise(resolve => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = {
                rayon_id: value.id_rayon,
                sous_rayon_id: value.id_subrayon,
                nom: 'Nouvelle etagere'
            };

            Vue.http.post(baseUrl + '/api/createEtagere', data)
                .then((response) => {
                    state.list_rayon[value.index_rayon].sous_rayons[value.index].etageres.push({
                        etagere_id: response.body.id,
                        etagere_nom: 'Nouvelle etagere',
                        produit: [],
                        type: '',
                        fondEcran: {}
                    });

                    dispatch('SEND_RAYONS_ORDER')
                        .then(() => {
                            resolve();
                        });
                },
                response => {
                    dispatch('NOTIFY', {
                        type: rootState.GeneralModule.notificationTypes.ERROR,
                        message: response.body.message
                    });
                });
        });
    },
    SET_UPDATE_RAYON_NAME: ({ dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            rayon_id: value.id,
            new_nom: value.new_name
        };

        Vue.http.put(baseUrl + '/api/updateRayonInformations', data)
            .then((response) => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: response.body.message
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_UPDATE_SUBRAYON_NAME: ({ dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            rayon_id: value.rayon_id,
            sous_rayon_id: value.sous_rayon_id,
            new_nom: value.new_name
        };

        Vue.http.put(baseUrl + '/api/updateSousRayonInformations', data)
            .then((response) => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: response.body.message
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_UPDATE_ETAGERE_NAME: ({ dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            etagere_id: value.etagere_id,
            new_nom: value.new_name,
            rayon_id: value.rayon_id,
            sous_rayon_id: value.sous_rayon_id,
            list_produits: value.list_produits
        };

        Vue.http.put(baseUrl + '/api/updateEtagere', data)
            .then((response) => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: response.body.message
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    DELETE_RAYON: ({ commit, dispatch, rootState }, value) => {
        return new Promise(resolve => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            Vue.http.delete(baseUrl + '/api/deleteRayon/' + value)
                .then(() => {
                    dispatch('SEND_RAYONS_ORDER');

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
    DELETE_SOUS_RAYON: ({ commit, dispatch, rootState }, value) => {
        return new Promise(resolve => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = {
                sous_rayon_id: value.sous_rayon_id,
                rayon_id: value.rayon_id
            };

            Vue.http.post(baseUrl + '/api/deleteSousRayon', data)
                .then(() => {
                    dispatch('SEND_RAYONS_ORDER');

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
    DELETE_ETAGERE: ({ commit, dispatch, rootState }, value) => {
        return new Promise(resolve => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = {
                etagere_id: value.etagere_id,
                rayon_id: value.rayon_id,
                sous_rayon_id: value.sous_rayon_id
            };

            Vue.http.post(baseUrl + '/api/deleteEtagere', data)
                .then(() => {
                    dispatch('SEND_RAYONS_ORDER');

                    resolve();
                },
                response => {
                    dispatch('NOTIFY', {
                        type: rootState.GeneralModule.notificationTypes.ERROR,
                        message: response.body.message
                    });
                });
        });
    }
};

const GestionRayons = {
    state,
    getters,
    mutations,
    actions
};

export default GestionRayons;
