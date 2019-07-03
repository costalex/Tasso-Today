import Vue from 'vue';

const state = {
    rayons_id: 0,
    sous_rayons_id: 0,
    rayons_nom: '',
    sous_rayons_nom: '',
    etagere_id: 0,
    etagere_nom: '',
    produits: []
};

const getters = {
    getRayonsdNom: (state) => state.rayons_nom,
    getSousRayonsNom: (state) => state.sous_rayons_nom,
    getEtagereNom: (state) => state.etagere_nom,
    getProduitsEtagere: (state) => state.produits
};

const mutations = {
    REST_ETAGERE: (state) => {
        state.produits = [];
    },
    SET_RAYONS_ID: (state, value) => {
        state.rayons_id = value;
    },
    SET_SOUS_RAYONS_ID: (state, value) => {
        state.sous_rayons_id = value;
    },
    SET_ETAGERE_ID: (state, value) => {
        state.etagere_id = value;
    },
    SET_RAYONS_NOM: (state, value) => {
        state.rayons_nom = value;
    },
    SET_SOUS_RAYONS_NOM: (state, value) => {
        state.sous_rayons_nom = value;
    },
    SET_ETAGERE_NOM: (state, value) => {
        state.etagere_nom = value;
    },
    SET_PRODUITS_ETAGERE: (state, value) => {
        state.produits = value;
    },
    SET_TAB_ETAGERE: (state, value) => {
        for (let i = 0; i < value.length; i++) {
            state.produits.push({
                id_position: i,
                position: value[i]
            });
        }
    },
    SET_RESET_MODULE: (state) => {
        state.rayons_id = 0;
        state.sous_rayons_id = 0;
        state.rayons_nom = '';
        state.sous_rayons_nom = '';
        state.etagere_id = 0;
        state.etagere_nom = '';
        state.produits = [];
    }
};

const actions = {
    DELETE_PRODUIT_ETAGERE: ({ state, commit, dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;
        const enseigneReference = rootState.DetailsShopModule.enseigne_reference;

        const data = {
            nom_produit: value.produit.nom,
            marque_produit: value.produit.marque,
            etagere_id: state.etagere_id,
            entreprise_id: enseigneReference,
            rayon_id: state.rayons_id,
            sous_rayon_id: state.sous_rayons_id,
            id_position: value.id_position
        };

        Vue.http.post(baseUrl + '/api/deleteProduitToEtagere', data)
            .then((response) => {
                commit('SET_PRODUITS_ETAGERE', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    ADD_PRODUIT_TO_ETAGERE: ({ state, commit, dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;
        const enseigneReference = rootState.DetailsShopModule.enseigne_reference;
        const save = JSON.parse(JSON.stringify(value));

        for (let i = 0; i in save; i++) {
            if (save[i].id_produit && save[i].id_produit.id) {
                const saveId = save[i].id_produit.id;
                save[i].id_produit = saveId;
            }
        }

        const data = {
            etagere_id: state.etagere_id,
            rayon_id: state.rayons_id,
            sous_rayon_id: state.sous_rayons_id,
            entreprise_id: enseigneReference,
            list_produits: save
        };

        Vue.http.post(baseUrl + '/api/addProduitToEtagere', data)
            .then((response) => {
                state.produits = response.body;
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    }
};

const EtagereModule = {
    state,
    getters,
    mutations,
    actions
};

export default EtagereModule;
