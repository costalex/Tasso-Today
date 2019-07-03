import Vue from 'vue';

const state = {
    select_rayon_client: 0,
    select_sous_rayon_client: 0,
    select_etagere_client: 0,
    list_store: {},
    select_produit_detail: {},
    city_shop: '',
    shop_name: '',
    shop_info: {},
    select_img: '',
    list_rayon: [],
    rayons: {
        rayon_id: 0,
        rayon_nom: '',
        sous_rayons: []
    },
    sous_rayons: {
        sous_rayon_id: 0,
        sous_rayon_nom: '',
        etageres: []
    },
    etageres: {
        etagere_id: 0,
        etagere_nom: '',
        produit: [],
        type: '',
        fondEcran: {}
    },
    etageres_aff: [],
    enseigne: {}
};

const getters = {
    getShopName: state => state.shop_name,
    getShopCity: state => state.city_shop,
    getShopInfo: state => state.shop_info,
    getEtagereBoutiqueClient: state => state.etageres_aff,
    getSelectImgClient: state => state.select_img
};

const mutations = {
    SET_SELECT_IMG: (state, selectImg) => {
        state.select_img = selectImg;
    },
    SET_CITY_SHOP: (state, cityShop) => {
        state.city_shop = cityShop;
    },
    SET_SHOP_INFO: (state, shopInfo) => {
        state.shop_info = shopInfo;
    },
    SET_SHOP_NAME: (state, shopName) => {
        state.shop_name = shopName;
    },
    SET_RAYON_CLIENT: (state, selectRayonClient) => {
        state.select_rayon_client = selectRayonClient;
    },
    SET_RESET_MODULE: (state) => {
        state.select_rayon_client = 0;
        state.select_sous_rayon_client = 0;
        state.select_etagere_client = 0;
        state.list_store = {};
        state.select_produit_detail = {};

        state.city_shop = '';
        state.shop_name = '';
        state.shop_info = {};

        state.select_img = '';

        state.list_rayon = [];

        state.rayons = {
            rayon_id: 0,
            rayon_nom: '',
            sous_rayons: []
        };

        state.sous_rayons = {
            sous_rayon_id: 0,
            sous_rayon_nom: '',
            etageres: []
        };

        state.etageres = {
            etagere_id: 0,
            etagere_nom: '',
            produit: [],
            type: '',
            fondEcran: {}
        };

        state.etageres_aff = [];
        state.enseigne = {};
    },
    SET_RESET_CLIENT_STORE: (state) => {
        state.select_rayon_client = 0;
        state.select_sous_rayon_client = 0;
        state.select_etagere_client = 0;
        state.select_produit_detail = {};

        state.list_rayon = [];

        state.etageres_aff = [];
        state.enseigne = {};
    },
    SET_SELECT_PRODUIT_DETAIL: (state, selectProduitDetail) => {
        state.select_produit_detail = selectProduitDetail;
    },
    SET_SOUS_RAYON_CLIENT: (state, selectSousRayonClient) => {
        state.select_sous_rayon_client = selectSousRayonClient;
    },
    SET_ETAGERE_AFF_BOUTIQUE_CLIENT: (state) => {
        state.etageres_aff =
            state.list_rayon[state.select_rayon_client].sous_rayons[state.select_sous_rayon_client].etageres;
    }
};

const actions = {
    SET_ENSEIGNE_DETAILS_FOR_CLIENT: ({ state, commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;
        const url = baseUrl + '/api/getShopInfosFor/' + state.city_shop + '/' + state.shop_name;

        Vue.http.get(url)
            .then((response) => {
                dispatch('SET_RAYONS_VENDEUR_CLIENT');
                commit('SET_SHOP_INFO', response.body);
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_RAYONS_VENDEUR_CLIENT: ({ state, commit, dispatch, rootState }) => {
        Vue.http.headers.common['Authorization'] = rootState.GeneralModule.TokenApi;
        Vue.http.headers.common['Content-Type'] = 'application/json';
        Vue.http.headers.common['Accept'] = 'application/json';

        state.select_rayon_client = 0;
        state.select_sous_rayon_client = 0;
        state.select_etagere_client = 0;

        const baseUrl = rootState.GeneralModule.BaseUrl;
        const url = baseUrl + '/api/getShopFor/' + state.city_shop + '/' + state.shop_name;

        Vue.http.get(url)
            .then((response) => {
                const rayons = response.body;

                state.list_rayon = [];

                const values = Object.keys(rayons).map(function (e) {
                    return rayons[e];
                });

                if (values.length !== 0) {
                    for (let rayonIndex in rayons) {
                        const rayon = rayons[rayonIndex];

                        for (let subRayonProperty in rayon) {
                            if (subRayonProperty === 'rayon_nom') {
                                state.rayons.rayon_nom = rayon.rayon_nom;
                            } else if (subRayonProperty === 'rayon_id') {
                                state.rayons.rayon_id = rayon.rayon_id;
                            } else {
                                const subRayon = rayon[subRayonProperty];

                                for (let etagereProperty in subRayon) {
                                    if (etagereProperty === 'sous_rayon_nom') {
                                        state.sous_rayons.sous_rayon_nom = subRayon.sous_rayon_nom;
                                    } else if (etagereProperty === 'sous_rayon_id') {
                                        state.sous_rayons.sous_rayon_id = subRayon.sous_rayon_id;
                                    } else {
                                        const etagere = subRayon[etagereProperty];

                                        state.etageres.etagere_id = etagere.etagere_id;
                                        state.etageres.etagere_nom = etagere.etagere_nom;
                                        state.etageres.produit = etagere.list_produits;
                                        state.etageres.type = etagere.type;
                                        state.etageres.fondEcran = etagere.fondEcran;
                                        state.sous_rayons.etageres.push(Object.assign({}, state.etageres));
                                    }
                                }

                                if (state.sous_rayons.etageres.length > 0) {
                                    state.rayons.sous_rayons.push(Object.assign({}, state.sous_rayons));
                                }

                                state.sous_rayons.sous_rayon_id = 0;
                                state.sous_rayons.sous_rayon_nom = '';
                                state.sous_rayons.etageres = [];
                            }
                        }

                        state.list_rayon.push(Object.assign({}, state.rayons));
                        state.rayons.rayon_id = 0;
                        state.rayons.rayon_nom = '';
                        state.rayons.sous_rayons = [];

                        commit('SET_ETAGERE_AFF_BOUTIQUE_CLIENT');
                    }
                }
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    }
};

const ClientStore = {
    state,
    getters,
    mutations,
    actions
};

export default ClientStore;
