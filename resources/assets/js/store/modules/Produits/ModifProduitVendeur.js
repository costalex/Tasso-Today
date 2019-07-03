import Vue from 'vue';

const state = {
    modif_produit: false,
    couleurs: [],
    produit: {}
};

const getters = {
    getModifProduit: state => state.modif_produit,
    getModifProduitTitle: state => state.produit.nom,
    getModifProduitDescription: state => state.produit.description,
    getProduitStock: state => state.produit.stocks,
    getModifProduitCouleurs: state => state.couleurs,
    getImagePrincipalModifProduit: state => state.produit.path_file_photo_principale,
    getImageSecondaireModifProduit: state => state.produit.path_file_photos_secondaire
};

const mutations = {
    SET_MODIF_PRODUIT: (state) => {
        state.modif_produit = !state.modif_produit;
    },
    SET_COULEURS: (state, couleurs) => {
        state.couleurs = couleurs;
    },
    SET_MODIF_PRODUIT_DATA: (state, value) => {
        state.produit.stocks = value;
    },
    SET_MODIF_PRODUIT_INFO: (state, value) => {
        state.produit = value;
    },
    SET_MODIF_PRODUIT_DESCRIPTION: (state, value) => {
        state.produit.description = value;
    },
    SET_RESET_MODULE: (state) => {
        state.modif_produit = false;
        state.couleurs = [];
        state.produit = {};
    }
};

const actions = {
    SET_SELECT_COLOR: ({ commit, dispatch, rootState }) => {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        Vue.http.get(baseUrl + '/api/getCouleurList')
            .then((response) => {
                commit('SET_COULEURS', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SEND_PRODUIT_MODIF: ({ state, commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            new_produit: state.produit
        };

        Vue.http.put(baseUrl + '/api/updateProduitsEntreprise', data)
            .then((response) => {
                commit('SET_MODIF_PRODUIT_INFO', {});
                commit('SET_ENSEIGNE_PRODUIT_MODIF', response.body);
                dispatch('SET_RAYONS_VENDEUR');
                dispatch('SET_ENSEIGNE_DETAILS');
                commit('SET_MODIF_PRODUIT');

                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: 'Le produit a été modifié'
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    }
};

const ModifyProductVendorModule = {
    state,
    getters,
    mutations,
    actions
};

export default ModifyProductVendorModule;
