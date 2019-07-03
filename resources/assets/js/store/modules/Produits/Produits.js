import Vue from 'vue';

const state = {
    select_famille: 0,
    select_categorie: 0,
    select_type: 0,
    select_name: '',
    produits: [],
    page_id: 0,
    page_num: 0,
    tab_save: [{ page_num: 0, id_produit: 0 }]
};

const getters = {
    getSelectFamille: state => state.select_famille,
    getSelectName: state => state.select_name,
    getPageId: state => state.page_id,
    getTabSave: state => state.tab_save
};

const mutations = {
    SET_SELECT_FAMILLE: (state, value) => {
        state.select_famille = value;
    },
    SET_SELECT_CATEGORIE: (state, value) => {
        state.select_categorie = value;
    },
    SET_SELECT_TYPE: (state, value) => {
        state.select_type = value;
    },
    SET_SELECT_NAME: (state, value) => {
        state.select_name = value;
    },
    SET_PAGE_ID: (state, value) => {
        state.page_id = value;
    },
    SET_TAB_SAVE: (state, value) => {
        state.tab_save.push(value);
    },
    SET_RESET_MODULE: (state) => {
        state.select_famille = 0;
        state.select_categorie = 0;
        state.select_type = 0;
        state.select_name = '';
        state.produits = [];
        state.page_id = 0;
        state.page_num = 0;
        state.tab_save = [{ page_num: 0, id_produit: 0 }];
    },
    SET_PRODUITS: (state, produits) => {
        state.produits = produits;
    },
    RESET_TAB_SAVE: (state) => {
        state.tab_save = [{ page_num: 0, id_produit: 0 }];
    }
};

const actions = {
    GET_PRODUITS: ({ state, commit, dispatch, rootState }) => {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            famille_id: state.select_famille,
            categorie_id: state.select_categorie,
            type_id: state.select_type,
            nom: state.select_name,
            id: state.page_id
        };

        Vue.http.post(baseUrl + '/api/produitsList', data)
            .then((response) => {
                if (response.body.length > 0) {
                    commit('SET_PRODUITS', response.body);
                }
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_TAB_ADD_PRODUIT: ({ state, commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            id_entreprise: rootState.DetailsShopModule.enseigne_reference,
            produits_filtre: state.produits
        };

        Vue.http.post(baseUrl + '/api/checkProduitsList', data)
            .then((response) => {
                commit('SET_PRODUITS', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_PRODUITS_HAVE: ({ state, commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            famille_id: state.select_famille,
            categorie_id: state.select_categorie,
            type_id: state.select_type,
            nom: state.select_name,
            id_entreprise: rootState.DetailsShopModule.enseigne_reference,
            id: state.page_id
        };

        Vue.http.post(baseUrl + '/api/produitsList', data)
            .then((response) => {
                commit('SET_PRODUITS', response.body);
                dispatch('SET_TAB_ADD_PRODUIT');
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    }
};

const ProductModule = {
    state,
    getters,
    mutations,
    actions
};

export default ProductModule;
