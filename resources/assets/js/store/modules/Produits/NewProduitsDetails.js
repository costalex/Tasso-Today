import Vue from 'vue';

const state = {
    Newproduit: false,
    NewProduitfamilles: 0,
    NewProduitcategories: 0,
    NewProduittypeproduits: 0,
    NewProduitunites: '',
    NewProduitMarque: '',
    NewProduitIdMarque: 0,
    NewProduitIndexation: 'PUBLIC',
    NewProduitDescription: '',
    NewProduitTitle: '',
    NewProduitImagePrincipal: '',
    NewProduitImageSecondaire: [],
    NewProduitHauteur: 0,
    NewProduitLongueur: 0,
    NewProduitLargeur: 0,
    NewProduitVolume: 0,
    NewProduitPoids: '',
    ref_modif_product: '',
    entreprise_id: 0,
    AffImagePrincipal: {},
    AffImageSecondaire: {}
};

const getters = {
    getAffImageSecondaire: state => state.AffImageSecondaire,
    getAffImagePrincipal: state => state.AffImagePrincipal,
    getNNewProduitunites: state => state.NewProduitunites,
    getNewProduitIndexation: state => state.NewProduitIndexation,
    getNewProduitMarque: state => state.NewProduitMarque,
    getNewProduitFamillie: state => state.NewProduitfamilles,
    getNewProduitCategorie: state => state.NewProduitcategories,
    getNewProduitType: state => state.NewProduittypeproduits,
    getNewProduitTitle: state => state.NewProduitTitle,
    getNewProduitDescription: state => state.NewProduitDescription,
    getNewProduit: state => state.Newproduit,
    getNewVolume: state => state.NewProduitVolume,
    getNewHauteur: state => state.NewProduitHauteur,
    getNewLongueur: state => state.NewProduitLongueur,
    getNewLargeur: state => state.NewProduitLargeur,
    getNewPoids: state => state.NewProduitPoids
};

const mutations = {
    SET_NEWPRODUIT: (state) => {
        state.Newproduit = !state.Newproduit;
    },
    SET_NEW_CATEGORIE: (state, value) => {
        state.NewProduitcategories = value;
    },
    SET_NEW_TYPE: (state, value) => {
        state.NewProduittypeproduits = value;
    },
    SET_NEW_FAMILLE: (state, value) => {
        state.NewProduitfamilles = value;
    },
    SET_NEW_UNITES: (state, value) => {
        state.NewProduitunites = value;
    },
    SET_NEW_INDEX: (state, value) => {
        state.NewProduitIndexation = value;
    },
    SET_NEW_MARQUE: (state, value) => {
        state.NewProduitMarque = value;
    },
    SET_NEW_MARQUE_ID: (state, value) => {
        state.NewProduitIdMarque = value;
    },
    SET_NEW_DESCRIPTION: (state, value) => {
        state.NewProduitDescription = value;
    },
    SET_NEW_TITLE: (state, value) => {
        state.NewProduitTitle = value;
    },
    SET_NEW_VOLUME: (state) => {
        state.NewProduitVolume = state.NewProduitLargeur * state.NewProduitLongueur * state.NewProduitHauteur;
    },
    SET_NEW_HAUTEUR: (state, value) => {
        state.NewProduitHauteur = value;
    },
    SET_NEW_LONGUEUR: (state, value) => {
        state.NewProduitLongueur = value;
    },
    SET_NEW_LARGEUR: (state, value) => {
        state.NewProduitLargeur = value;
    },
    SET_NEW_POIDS: (state, value) => {
        state.NewProduitPoids = value;
    },
    SET_NEW_IMAGEPRINCIPAL: (state, value) => {
        state.NewProduitImagePrincipal = value;
    },
    SET_NEW_IMAGESECONDAIRE: (state, value) => {
        state.NewProduitImageSecondaire = value;
    },
    SET_IMAGES_SECONDAIRES: (state, value) => {
        state.AffImageSecondaire = value;
    },
    RESET_INFO_MODIF_PRODUIT: (state) => {
        state.NewProduitfamilles = 0;
        state.NewProduitcategories = 0;
        state.NewProduittypeproduits = 0;
        state.NewProduitunites = '';
        state.NewProduitMarque = '';
        state.NewProduitIdMarque = 0;
        state.NewProduitIndexation = 'PUBLIC';

        state.NewProduitDescription = '';
        state.NewProduitTitle = '';

        state.NewProduitImagePrincipal = '';
        state.NewProduitImageSecondaire = [];

        state.NewProduitHauteur = 0;
        state.NewProduitLongueur = 0;
        state.NewProduitLargeur = 0;
        state.NewProduitVolume = 0;
        state.NewProduitPoids = '';

        state.ref_modif_product = '';
        state.entreprise_id = 0;
    },
    SET_RESET_MODULE: (state) => {
        state.Newproduit = false;

        state.NewProduitfamilles = 0;
        state.NewProduitcategories = 0;
        state.NewProduittypeproduits = 0;
        state.NewProduitunites = '';
        state.NewProduitMarque = '';
        state.NewProduitIdMarque = 0;
        state.NewProduitIndexation = 'PUBLIC';

        state.NewProduitDescription = '';
        state.NewProduitTitle = '';

        state.NewProduitImagePrincipal = '';
        state.NewProduitImageSecondaire = [];

        state.NewProduitHauteur = 0;
        state.NewProduitLongueur = 0;
        state.NewProduitLargeur = 0;
        state.NewProduitVolume = 0;
        state.NewProduitPoids = '';

        state.ref_modif_product = '';
        state.entreprise_id = 0;
        state.AffImagePrincipal = {};
        state.AffImageSecondaire = {};
    },
    SET_INFO_PRODUIT: (state, value) => {
        state.NewProduitfamilles = value.famille.id;
        state.NewProduitcategories = value.categorie.id;

        if (value.type !== null) {
            state.NewProduittypeproduits = value.type.id;
        }

        state.NewProduitunites = value.unite_mesure;
        state.NewProduitMarque = value.marque.nom;
        state.NewProduitIdMarque = value.marque.id;
        state.NewProduitIndexation = value.status;

        state.NewProduitDescription = value.description;
        state.NewProduitTitle = value.nom;

        state.AffImagePrincipal = value.path_file_photo_principale;
        state.AffImageSecondaire = value.path_file_photos_secondaire;

        state.NewProduitHauteur = value.hauteur;
        state.NewProduitLongueur = value.longueur;
        state.NewProduitLargeur = value.largeur;
        state.NewProduitVolume = value.volume;
        state.NewProduitPoids = value.poids;

        state.ref_modif_product = value.ref_produit;
        state.entreprise_id = value.entreprise_id;
    }
};

const actions = {
    INITIALIZE_INFO_PRODUIT: ({ state, commit, dispatch, rootState }, value) => {
        return new Promise((resolve, reject) => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            Vue.http.get(baseUrl + '/api/getGenProduitInfos/' + value)
                .then((response) => {
                    commit('SET_INFO_PRODUIT', response.body);

                    dispatch('INITIALIZE_LIST_CATEGORIE', response.body.famille.id);
                    dispatch('INITIALIZE_LIST_TYPE', response.body.categorie.id);

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
    SET_ADD_MARQUE_FOR_MODIF: ({ state, commit, dispatch, rootState }) => {
        return new Promise((resolve, reject) => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = {
                nom: state.NewProduitMarque
            };

            Vue.http.post(baseUrl + '/api/marqueAdd', data)
                .then(response => {
                    commit('SET_NEW_MARQUE_ID', response.body.id);

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
    SEND_INFO_MODIF_PRODUIT: ({ state, commit, dispatch, rootState }) => {
        return new Promise((resolve, reject) => {
            const baseUrl = rootState.GeneralModule.BaseUrl;

            const data = {
                ref_produit: state.ref_modif_product,
                nom: state.NewProduitTitle,
                description: state.NewProduitDescription,
                famille_id: state.NewProduitfamilles,
                categorie_id: state.NewProduitcategories,
                type_id: state.NewProduittypeproduits,
                marque: state.NewProduitMarque,
                marque_id: state.NewProduitIdMarque,
                status: state.NewProduitIndexation,
                entreprise_id: state.entreprise_id,
                poids: state.NewProduitPoids,
                longueur: state.NewProduitLongueur,
                largeur: state.NewProduitLargeur,
                hauteur: state.NewProduitHauteur,
                volume: state.NewProduitVolume,
                unite_mesure: state.NewProduitunites,
                path_file_photo_principale: state.NewProduitImagePrincipal,
                path_file_photos_secondaire: state.NewProduitImageSecondaire
            };

            Vue.http.put(baseUrl + '/api/updateGenProduit', data)
                .then((response) => {
                    commit('SET_NEW_MARQUE_ID', response.body.marque_id);
                    commit('RESET_INFO_MODIF_PRODUIT');
                    dispatch('GET_PRODUITS');

                    dispatch('NOTIFY', {
                        type: rootState.GeneralModule.notificationTypes.SUCCESS,
                        message: response.body.message
                    });

                    resolve();
                },
                response => {
                    if (response.body.message !== 'Type inconnue.') {
                        dispatch('NOTIFY', {
                            type: rootState.GeneralModule.notificationTypes.ERROR,
                            message: response.body.message
                        });

                        reject(response);
                    } else {
                        dispatch('NOTIFY', {
                            type: rootState.GeneralModule.notificationTypes.SUCCESS,
                            message: 'Produit generique mis a jour.'
                        });

                        resolve();
                    }
                });
        });
    },
    SET_ADD_MARQUE: ({ state, commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            nom: state.NewProduitMarque
        };

        Vue.http.post(baseUrl + '/api/marqueAdd', data)
            .then((response) => {
                commit('SET_NEW_MARQUE_ID', response.body.id);
                dispatch('SET_ADD_PRODUIT');
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    SET_ADD_PRODUIT: ({ state, commit, dispatch, rootState }) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            id_entreprise: rootState.DetailsShopModule.enseigne_reference,
            famille_id: state.NewProduitfamilles,
            categorie_id: state.NewProduitcategories,
            type_id: state.NewProduittypeproduits,
            unite_mesure: state.NewProduitunites,
            marque_id: state.NewProduitIdMarque,
            status: state.NewProduitIndexation,
            description: state.NewProduitDescription,
            nom: state.NewProduitTitle,
            path_file_photo_principale: state.NewProduitImagePrincipal,
            path_file_photos_secondaire: state.NewProduitImageSecondaire,
            hauteur: parseFloat(state.NewProduitHauteur, 10),
            longueur: parseFloat(state.NewProduitLongueur, 10),
            largeur: parseFloat(state.NewProduitLargeur, 10),
            volume: parseFloat(state.NewProduitVolume, 10),
            poids: parseFloat(state.NewProduitPoids, 10)
        };

        Vue.http.post(baseUrl + '/api/produitsInfos', data)
            .then(() => {
                dispatch('SET_ENSEIGNE_DETAILS');
                commit('SET_NEWPRODUIT', false);

                commit('SET_NEW_FAMILLE', 0);
                commit('SET_NEW_CATEGORIE', 0);
                commit('SET_NEW_TYPE', 0);
                commit('SET_NEW_UNITES', '');
                commit('SET_NEW_MARQUE', '');
                commit('SET_NEW_MARQUE_ID', 0);
                commit('SET_NEW_INDEX', 'PUBLIC');

                commit('SET_NEW_DESCRIPTION', '');
                commit('SET_NEW_TITLE', '');

                commit('SET_NEW_IMAGEPRINCIPAL', '');
                commit('SET_NEW_IMAGESECONDAIRE', []);

                commit('SET_NEW_HAUTEUR', 0);
                commit('SET_NEW_LONGUEUR', 0);
                commit('SET_NEW_LARGEUR', 0);
                commit('SET_NEW_VOLUME');
                commit('SET_NEW_POIDS');

                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.SUCCESS,
                    message: 'Le produit a été ajouté'
                });
            },
            response => {
                dispatch('NOTIFY', {
                    type: rootState.GeneralModule.notificationTypes.ERROR,
                    message: response.body.message
                });
            });
    },
    DELETE_IMAGE: ({ state, commit, dispatch, rootState }, value) => {
        const baseUrl = rootState.GeneralModule.BaseUrl;

        const data = {
            img_target: value,
            ref_produit: state.ref_modif_product
        };

        Vue.http.post(baseUrl + '/api/deleteImageProduit', data)
            .then(() => {
                const affImageSecondaire = [...state.AffImageSecondaire];

                if (affImageSecondaire[0].image === value) {
                    affImageSecondaire[0].image = '';
                } else {
                    affImageSecondaire[1].image = '';
                }

                commit('SET_IMAGES_SECONDAIRES', affImageSecondaire);
            });
    }
};

const NewProductDetailsModule = {
    state,
    getters,
    mutations,
    actions
};

export default NewProductDetailsModule;
