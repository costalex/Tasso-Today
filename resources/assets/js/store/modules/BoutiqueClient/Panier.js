import store from '@/store';

const state = {
    general_total: 0,
    general_quantite: 0,
    panier_general: [],
    panier_enseigne: {
        total: 0,
        list_promo: [],
        commentaire: '',
        quantite_total: 0,
        product: [],
        poid_panier: 0
    },
    product: {
        nom: '',
        img: {},
        quantite: 0,
        id_produit: 0,
        stocks: {
            model: '',
            prix: 0,
            id: 0,
            couleur: {},
            poids: 0,
            stock: 0
        }
    }
};

const getters = {
    getPanierGeneralClient: state => state.panier_general
};

const mutations = {
    SET_PRODUCT_NAME_FOR_PANIER: (state, value) => {
        state.product.nom = value;
    },
    SET_PRODUCT_IMG_FOR_PANIER: (state, value) => {
        state.product.img = value;
    },
    SET_PRODUCT_QUANTITE_FOR_PANIER: (state, value) => {
        state.product.quantite = value;
    },
    SET_PRODUCT_MODEL_FOR_PANIER: (state, value) => {
        state.product.stocks.model = value.model;
        state.product.stocks.prix = value.prix;
        state.product.stocks.id = value.id;
        state.product.stocks.couleur = value.couleur;
        state.product.stocks.poids = value.poids;
        state.product.stocks.stock = value.stock;
    },
    INCREMENT_PRODUCT_PANIER: (state, value) => {
        state.panier_enseigne.total = 0;
        state.panier_enseigne.quantite_total = 0;
        state.panier_enseigne.poid_panier = 0;

        for (let i = 0; i < state.panier_enseigne.product.length; i++) {
            const produit = state.panier_enseigne.product[i];

            if (produit.id_produit === value.id_produit &&
                produit.stocks.id === value.stocks.id &&
                produit.quantite <= produit.stocks.stock
            ) {
                produit.quantite++;
            }

            state.panier_enseigne.poid_panier += produit.quantite * produit.stocks.poids;
            state.panier_enseigne.total += produit.quantite * produit.stocks.prix;
            state.panier_enseigne.quantite_total += produit.quantite;
        }
    },
    DECREMENT_PRODUCT_PANIER: (state, value) => {
        state.panier_enseigne.total = 0;
        state.panier_enseigne.quantite_total = 0;
        state.panier_enseigne.poid_panier = 0;

        for (let i = 0; i < state.panier_enseigne.product.length; i++) {
            const produit = state.panier_enseigne.product[i];

            if (produit.id_produit === value.id_produit &&
                produit.stocks.id === value.stocks.id
            ) {
                produit.quantite--;
            }

            state.panier_enseigne.poid_panier += produit.quantite * produit.stocks.poids;
            state.panier_enseigne.total += produit.quantite * produit.stocks.prix;
            state.panier_enseigne.quantite_total += produit.quantite;
        }
    },
    RESET_PANIER: (state) => {
        state.panier_enseigne.list_promo = [];
        state.panier_enseigne.commentaire = '';
        state.panier_enseigne.product = [];
        state.panier_enseigne.quantite_total = 0;
        state.panier_enseigne.total = 0;
        state.panier_enseigne.poid_panier = 0;
    },
    SET_PRODUCT_ID_FOR_CLIENT: (state, value) => {
        state.product.id_produit = value;
    },
    SET_PANIER_ENSEIGNE: (state) => {
        for (let i = 0; i < state.panier_general.length; i++) {
            const panier = state.panier_general[i];

            if (panier.shop.name === store.getters.getShopName &&
                panier.shop.city === store.getters.getShopCity
            ) {
                state.panier_enseigne.poid_panier = panier.poid_panier;
                state.panier_enseigne.product = panier.product;
                state.panier_enseigne.quantite_total = panier.quantite_total;
                state.panier_enseigne.total = panier.total;
            }
        }
    },
    SET_COMMENTAIRE_PANIER_IN_GENERAL: (state, value) => {
        for (let i = 0; i < state.panier_general.length; i++) {
            const panier = state.panier_general[i];

            if (panier.shop.name === value.shopname) {
                panier.commentaire = value.commentaire;
            }
        }
    },
    SET_GENERAL_PANIER_CLIENT: (state, value) => {
        state.panier_general = value;
        state.general_quantite = 0;
        state.general_total = 0;

        for (let i = 0; i < state.panier_general.length; i++) {
            const panier = state.panier_general[i];

            state.general_quantite += panier.quantite_total;
            state.general_total += panier.total;
        }
    },
    ADD_TO_GENERAL_PANIER: (state) => {
        let add = false;
        state.general_quantite = 0;
        state.general_total = 0;

        for (let i = 0; i < state.panier_general.length; i++) {
            const panier = state.panier_general[i];

            if (panier.shop.name === store.getters.getShopName &&
                panier.shop.city === store.getters.getShopCity
            ) {
                panier.product.list_promo = state.panier_enseigne.list_promo;
                panier.product.commentaire = state.panier_enseigne.commentaire;
                panier.product = state.panier_enseigne.product;
                panier.total = state.panier_enseigne.total;
                panier.quantite_total = state.panier_enseigne.quantite_total;
                panier.poid_panier = state.panier_enseigne.poid_panier;

                add = true;
            }

            if (panier.product.length === 0) {
                state.panier_general.splice(i, 1);
            } else {
                state.general_quantite += panier.quantite_total;
                state.general_total += panier.total;
            }
        }

        if (!add && state.panier_enseigne.product.length > 0) {
            state.general_quantite += state.panier_enseigne.quantite_total;
            state.general_total += state.panier_enseigne.total;

            state.panier_general.push({
                quantite_total: state.panier_enseigne.quantite_total,
                total: state.panier_enseigne.total,
                product: state.panier_enseigne.product,
                poid_panier: state.panier_enseigne.poid_panier,
                list_promo: state.panier_enseigne.list_promo,
                commentaire: state.panier_enseigne.commentaire,
                shop: {
                    name: store.getters.getShopName,
                    city: store.getters.getShopCity
                }
            });
        }
    },
    ADD_TO_PANIER: (state) => {
        let add = false;
        state.panier_enseigne.total = 0;
        state.panier_enseigne.quantite_total = 0;
        state.panier_enseigne.poid_panier = 0;

        for (let i = 0; i < state.panier_enseigne.product.length; i++) {
            const produit = state.panier_enseigne.product[i];

            if (produit.id_produit === state.product.id_produit &&
                produit.stocks.id === state.product.stocks.id
            ) {
                if (produit.quantite <= state.product.stocks.stock) {
                    produit.quantite += state.product.quantite;
                }

                add = true;
            }

            state.panier_enseigne.poid_panier += produit.quantite * produit.stocks.poids;
            state.panier_enseigne.total += produit.quantite * produit.stocks.prix;
            state.panier_enseigne.quantite_total += produit.quantite;
        }

        if (!add) {
            state.panier_enseigne.poid_panier += state.product.quantite * state.product.stocks.poids;
            state.panier_enseigne.total += state.product.quantite * state.product.stocks.prix;
            state.panier_enseigne.quantite_total += state.product.quantite;

            state.panier_enseigne.product.push(JSON.parse(JSON.stringify(state.product)));
        }
    },
    DELETE_PRODUCT_PANIER: (state, value) => {
        for (let i = 0; i < state.panier_enseigne.product.length; i++) {
            const produit = state.panier_enseigne.product[i];

            if (produit.id_produit === value.id_produit &&
                produit.stocks.id === value.stocks.id
            ) {
                state.panier_enseigne.poid_panier -= produit.quantite * produit.stocks.poids;
                state.panier_enseigne.total -= produit.quantite * produit.stocks.prix;
                state.panier_enseigne.quantite_total -= produit.quantite;

                state.panier_enseigne.product.splice(i, 1);
            }
        }
    },
    SET_PANIER_ENSEIGNE_CLIENT: (state, value) => {
        state.panier_enseigne.product = value;
    },
    RESET_PRODUCT_CLIENT: (state) => {
        state.product = {
            nom: '',
            img: {},
            quantite: 0,
            id_produit: 0,
            stocks: {
                model: '',
                prix: 0,
                id: 0,
                couleur: {},
                poids: 0,
                stock: 0
            }
        };
    },
    DELETE_PANIER_BY_SHOP: (state, value) => {
        state.general_quantite = 0;
        state.general_total = 0;

        for (let i = 0; i < state.panier_general.length; i++) {
            const panier = state.panier_general[i];

            if (panier.shop.name === value) {
                state.panier_general.splice(i, 1);
            } else {
                state.general_quantite += panier.quantite_total;
                state.general_total += panier.total;
            }
        }
    },
    INCREMENT_PRODUCT_PANIER_GENERAL: (state, value) => {
        state.general_quantite = 0;
        state.general_total = 0;

        for (let i = 0; i < state.panier_general.length; i++) {
            const panier = state.panier_general[i];

            if (panier.shop.name === value.shopname) {
                panier.poid_panier = 0;
                panier.total = 0;
                panier.quantite_total = 0;

                for (let j = 0; j < panier.product.length; j++) {
                    const produit = panier.product[j];

                    if (produit.id_produit === value.product.id_produit &&
                        produit.stocks.id === value.product.stocks.id &&
                        produit.quantite < produit.stocks.stock
                    ) {
                        produit.quantite++;
                    }

                    panier.poid_panier += produit.quantite * produit.stocks.poids;
                    panier.total += produit.quantite * produit.stocks.prix;
                    panier.quantite_total += produit.quantite;
                }
            }

            state.general_quantite += panier.quantite_total;
            state.general_total += panier.total;
        }
    },
    DECREMENT_PRODUCT_PANIER_GENERAL: (state, value) => {
        state.general_quantite = 0;
        state.general_total = 0;

        for (let i = 0; i < state.panier_general.length; i++) {
            const panier = state.panier_general[i];

            if (panier.shop.name === value.shopname) {
                panier.total = 0;
                panier.quantite_total = 0;

                for (let j = 0; j < panier.product.length; j++) {
                    const produit = panier.product[j];

                    if (produit.id_produit === value.product.id_produit &&
                        produit.stocks.id === value.product.stocks.id &&
                        produit.quantite > 1
                    ) {
                        produit.quantite--;
                    }

                    panier.poid_panier += produit.quantite * produit.stocks.poids;
                    panier.total += produit.quantite * produit.stocks.prix;
                    panier.quantite_total += produit.quantite;
                }
            }

            if (panier.product.length === 0) {
                state.panier_general.splice(i, 1);
            } else {
                state.general_quantite += panier.quantite_total;
                state.general_total += panier.total;
            }
        }
    },
    DELETE_PRODUCT_PANIER_GENERAL: (state, value) => {
        state.general_quantite = 0;
        state.general_total = 0;

        for (let i = 0; i < state.panier_general.length; i++) {
            const panier = state.panier_general[i];

            if (panier.shop.name === value.shopname) {
                for (let j = 0; j < panier.product.length; j++) {
                    const produit = panier.product[j];

                    if (produit.id_produit === value.product.id_produit &&
                        produit.stocks.id === value.product.stocks.id
                    ) {
                        panier.poid_panier -= produit.quantite * produit.stocks.poids;
                        panier.total -= produit.quantite * produit.stocks.prix;
                        panier.quantite_total -= produit.quantite;

                        panier.product.splice(j, 1);
                    }
                }
            }

            if (panier.product.length === 0) {
                state.panier_general.splice(i, 1);
            } else {
                state.general_quantite += panier.quantite_total;
                state.general_total += panier.total;
            }
        }
    }
};

const Panier = {
    state,
    getters,
    mutations
};

export default Panier;
