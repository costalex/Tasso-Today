<template>
    <div id="VendorSectionsBody">
        <div
            v-if="subPage === 'ailses'"
            id="TopBoddyRayons"
        >
            <div class="col-md-12 col-sm-12 no-padding shelves-count">
                <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                    <p>
                        <i
                            class="fa fa-bars fa-lg"
                            aria-hidden="true"
                        />
                        Rayon(s) ({{ Rayons.length }})
                    </p>
                </div>

                <div
                    class="col-md-6 col-sm-6 col-xs-6 no-padding"
                    align="right"
                >
                    <button
                        class="btn-add-shelf"
                        @click="addRayon"
                    >
                        <i
                            class="fa fa-plus-circle fa-lg"
                            aria-hidden="true"
                        />
                        Créer un rayon
                    </button>
                </div>
            </div>

            <draggable
                v-model="Rayons"
                @end="updateRayon"
            >
                <transition-group>
                    <div
                        v-for="(rayon, id_rayon) in Rayons"
                        :key="id_rayon"
                        class="col-md-12 col-sm-12 col-xs-12"
                        @end="updateRayon"
                    >
                        <div>
                            <div class="col-md-10 col-sm-10 col-xs-10 shelf-name">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div class="col-md-8 col-sm-8 col-xs-12 no-padding">
                                        <input
                                            :id="rayon.rayon_nom + id_rayon + rayon.rayon_id"
                                            v-model.lazy="rayon.rayon_nom"
                                            type="text"
                                            @change="updateSelectRayon(id_rayon)"
                                        >
                                    </div>

                                    <div class="col-md-4 col-sm-4 col-xs-12 no-padding">
                                        <label :for="rayon.rayon_nom + id_rayon + rayon.rayon_id">
                                            <i
                                                class="fa fa-pencil"
                                                aria-hidden="true"
                                            />
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3 col-sm-4 col-xs-4">
                                    <p>{{ rayon.sous_rayons.length }} sous-rayon(s)</p>
                                </div>

                                <div class="col-md-1 col-sm-1 col-xs-1" />
                                <div
                                    class="col-md-3 col-sm-3 col-xs-3"
                                    align="right"
                                >
                                    <button @click="selectRayon(rayon.rayon_id)">
                                        <i
                                            class="fa fa-angle-down fa-lg"
                                            aria-hidden="true"
                                        />
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-2 col-xs-2 btn-delete-shelf">
                                <button
                                    data-toggle="modal"
                                    data-target="#ModalDeleteObj"
                                    @click="deleteRayon(Rayons, id_rayon)"
                                >
                                    <i
                                        class="fa fa-times-circle fa-lg"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </div>

                        <div class="col-md-12 col-sm-12 col-xs-12 sub-shelves-count">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <p v-if="select_rayon === rayon.rayon_id">
                                    <i
                                        class="fa fa-bars fa-lg"
                                        aria-hidden="true"
                                    />
                                    Sous-rayon(s)({{ rayon.sous_rayons.length }})
                                </p>
                            </div>

                            <div
                                class="col-md-6 col-sm-6 col-xs-6 no-padding"
                                align="right"
                            >
                                <button
                                    v-if="select_rayon === rayon.rayon_id"
                                    class="btn-add-shelf"
                                    @click="addSubRayon(id_rayon)"
                                >
                                    <i
                                        class="fa fa-plus-circle fa-lg"
                                        aria-hidden="true"
                                    />
                                    Créer un sous-rayon
                                </button>
                            </div>
                        </div>

                        <draggable
                            v-if="select_rayon === rayon.rayon_id"
                            v-model="rayon.sous_rayons"
                        >
                            <transition-group>
                                <div
                                    v-for="(subrayon, id_subrayon) in rayon.sous_rayons"
                                    :key="id_subrayon"
                                    class="col-md-12 col-sm-12 col-xs-12"
                                >
                                    <div style="padding-left: 50px;">
                                        <div class="col-md-10 col-sm-10 col-xs-10 sub-shelf-name">
                                            <div class="col-md-4 col-sm-5 col-xs-3">
                                                <div class="col-md-10 col-sm-10 col-xs-12 no-padding">
                                                    <input
                                                        :id="subrayon.sous_rayon_nom + id_subrayon + id_rayon + rayon.rayon_id"
                                                        v-model.lazy="subrayon.sous_rayon_nom"
                                                        type="text"
                                                        @change="updateSelectSubRayon(id_rayon, id_subrayon)"
                                                    >
                                                </div>

                                                <div class="col-md-2 col-sm-2 col-xs-12 no-padding">
                                                    <label :for="subrayon.sous_rayon_nom + id_subrayon + id_rayon + rayon.rayon_id">
                                                        <i
                                                            class="fa fa-pencil col-md-3 col-sm-3 col-xs-3"
                                                            aria-hidden="true"
                                                        />
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-3 col-sm-3 col-xs-3">
                                                <p>{{ subrayon.etageres.length }} étagère(s)</p>
                                            </div>

                                            <div
                                                class="col-md-2 col-sm-2 col-xs-2"
                                                align="right"
                                            >
                                                <button @click.stop="selectSubRayon(subrayon.sous_rayon_id)">
                                                    <i
                                                        class="fa fa-angle-down fa-lg"
                                                        aria-hidden="true"
                                                    />
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col-md-2 col-sm-2 col-xs-2 btn-delete-shelf">
                                            <button
                                                data-toggle="modal"
                                                data-target="#ModalDeleteObj"
                                                @click="deleteSousRayon(rayon, id_rayon, id_subrayon)"
                                            >
                                                <i
                                                    class="fa fa-times-circle fa-lg"
                                                    aria-hidden="true"
                                                />
                                            </button>
                                        </div>
                                    </div>

                                    <draggable
                                        v-if="select_subrayon === subrayon.sous_rayon_id"
                                        v-model="subrayon.etageres"
                                    >
                                        <transition-group>
                                            <div
                                                v-for="(item, id_etagere) in subrayon.etageres"
                                                :key="id_etagere"
                                                class="col-md-12 col-sm-12 col-xs-12"
                                                @click="selectEtagere(item.etagere_id)"
                                            >
                                                <div style="padding-left: 50px;">
                                                    <div class="col-md-6 col-sm-6 col-xs-6 row-name">
                                                        <div class="col-md-8 col-sm-8 col-xs-8">
                                                            <div class="col-md-7 col-sm-7 col-xs-12 no-padding">
                                                                <input
                                                                    :id="item.etagere_nom + id_etagere + id_subrayon + subrayon.sous_rayon_id + id_rayon +rayon.rayon_id"
                                                                    v-model.lazy="item.etagere_nom"
                                                                    type="text"
                                                                    @change="updateSelectEtagerRayon(id_rayon, id_subrayon, id_etagere)"
                                                                >
                                                            </div>

                                                            <div class="col-md-4 col-sm-4 col-xs-12 no-padding">
                                                                <label :for="item.etagere_nom + id_etagere + id_subrayon + subrayon.sous_rayon_id + id_rayon +rayon.rayon_id">
                                                                    <i
                                                                        class="fa fa-pencil col-md-2 col-sm-2 col-xs-2 col-md-offset-2"
                                                                        aria-hidden="true"
                                                                    />
                                                                </label>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                                            <button @click="editeEtagere(id_rayon, id_subrayon, id_etagere)">
                                                                Editer
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4 col-sm-4 col-xs-4 btn-delete-shelf">
                                                        <button
                                                            data-toggle="modal"
                                                            data-target="#ModalDeleteObj"
                                                            @click="deleteEtagere(subrayon, id_rayon, id_subrayon, id_etagere)"
                                                        >
                                                            <i
                                                                class="fa fa-times-circle fa-lg"
                                                                aria-hidden="true"
                                                            />
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </transition-group>
                                    </draggable>

                                    <div
                                        class="col-md-12 col-sm-12 col-xs-12"
                                        align="center"
                                    >
                                        <button
                                            v-if="select_subrayon === subrayon.sous_rayon_id"
                                            class="btn btn-add-row"
                                            @click="addEtagere(id_rayon, id_subrayon)"
                                        >
                                            <i
                                                class="fa fa-plus-circle fa-lg"
                                                aria-hidden="true"
                                            />
                                            Ajouter une étagère
                                        </button>
                                    </div>
                                </div>
                            </transition-group>
                        </draggable>
                    </div>
                </transition-group>
            </draggable>
        </div>

        <div v-else-if="subPage === 'shelve'">
            <MenuEtagere @back-to-ailses="subPage = 'ailses'" />
        </div>

        <ModalDeleteObj
            v-show="showModal"
            id="ModalDeleteObj"
            :obj="obj_delete"
            class="modal fade"
            role="dialog"
        />
    </div>
</template>

<script>
import draggable from 'vuedraggable';
import { mapGetters } from 'vuex';
import MenuEtagere from '@/components/vendor/PageAilsesManagement/ShelvesMenu';
import ModalDeleteObj from '@/components/vendor/PageAilsesManagement/DeleteObjectModal';

export default {
    name: 'AisleManagement',
    components: {
        draggable,
        ModalDeleteObj,
        MenuEtagere
    },
    data: () => {
        return {
            showModal: false,
            obj_delete: {
                name: '',
                type: '',
                arg: {}
            },
            drag: {},
            select_subrayon: -1,
            select_rayon: -1,
            select_etagere: -1,
            subPage: 'ailses'
        };
    },
    computed: {
        ...mapGetters([
            'notificationTypes'
        ]),
        Rayons: {
            set (value) {
                this.$store.commit('SET_LIST_RAYON', value);
            },
            get () {
                return this.$store.getters.getRayonsDetails;
            }
        }
    },
    methods: {
        /**
         * Add
         */
        addRayon () {
            this.$store.dispatch('SET_CREATE_RAYON', { id: this.Rayons.length })
                .then(() => {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.SUCCESS,
                        message: 'Le rayon a été ajouté'
                    });
                });
        },
        addSubRayon (rayonIndex) {
            this.$store.dispatch('SET_CREATE_SOUS_RAYON', {
                rayon_id: this.Rayons[rayonIndex].rayon_id,
                id: rayonIndex,
                index: (this.Rayons[rayonIndex].sous_rayons.length - 1)
            })
                .then(() => {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.SUCCESS,
                        message: 'Le sous-rayon a été ajouté'
                    });
                });
        },
        addEtagere (rayonIndex, subRayonIndex) {
            this.$store.dispatch('SET_CREATE_ETAGERE', {
                id_rayon: this.Rayons[rayonIndex].rayon_id,
                id_subrayon: this.Rayons[rayonIndex].sous_rayons[subRayonIndex].sous_rayon_id,
                index_rayon: rayonIndex,
                index: subRayonIndex
            })
                .then(() => {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.SUCCESS,
                        message: 'L\'étagère a été ajoutée'
                    });
                });
        },
        /**
         * Select
         */
        selectRayon (index) {
            if (this.select_rayon !== index) {
                this.select_subrayon = -1;
                this.select_etagere = -1;
            }

            if (this.select_rayon === index) {
                this.select_rayon = -1;
                this.select_subrayon = -1;
                this.select_etagere = -1;
            } else {
                this.select_rayon = index;
            }
        },
        selectSubRayon (index) {
            if (this.select_subrayon === index) {
                this.select_subrayon = -1;
                this.select_etagere = -1;
            } else {
                this.select_subrayon = index;
                this.select_etagere = -1;
            }
        },
        selectEtagere (index) {
            this.select_etagere = index;
        },
        /**
         * Update
         */
        updateRayon () {
            this.$store.dispatch('SET_RAYONS_ORDER', this.Rayons)
                .then(() => {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.SUCCESS,
                        message: 'L\'ordre des rayons a été modifié'
                    });
                });
        },
        updateSelectRayon (index) {
            this.$store.dispatch('SET_UPDATE_RAYON_NAME', {
                new_name: this.Rayons[index].rayon_nom,
                id: this.Rayons[index].rayon_id
            });

            this.$store.dispatch('SET_RAYONS_ORDER', this.Rayons);
        },
        updateSelectSubRayon (rayonIndex, subRayonIndex) {
            this.$store.dispatch('SET_UPDATE_SUBRAYON_NAME', {
                new_name: this.Rayons[rayonIndex].sous_rayons[subRayonIndex].nom,
                sous_rayon_id: this.Rayons[rayonIndex].sous_rayons[subRayonIndex].sous_rayon_id,
                rayon_id: this.Rayons[rayonIndex].rayon_id
            });

            this.$store.dispatch('SET_RAYONS_ORDER', this.Rayons);
        },
        updateSelectEtagerRayon (rayonIndex, subRayonIndex, etagereIndex) {
            this.$store.dispatch('SET_UPDATE_ETAGERE_NAME', {
                rayon_id: this.Rayons[rayonIndex].rayon_id,
                sous_rayon_id: this.Rayons[rayonIndex].sous_rayons[subRayonIndex].sous_rayon_id,
                new_name: this.Rayons[rayonIndex].sous_rayons[subRayonIndex].etageres[etagereIndex].etagere_nom,
                list_produits: this.Rayons[rayonIndex].sous_rayons[subRayonIndex].etageres[etagereIndex].list_produits,
                etagere_id: this.Rayons[rayonIndex].sous_rayons[subRayonIndex].etageres[etagereIndex].etagere_id
            });

            this.$store.dispatch('SET_RAYONS_ORDER', this.Rayons);
        },
        /**
         * Delete
         */
        deleteRayon (tab, rayonIndex) {
            this.obj_delete.type = 'Rayon';
            this.obj_delete.name =
                'Voulez-vous réellement effacer le Rayon : "' + this.Rayons[rayonIndex].rayon_nom + '".';
            this.obj_delete.message = 'Cela entraînera la suppression de tout le contenu de ce dernier.';
            this.obj_delete.arg = {
                arg_commit: this.Rayons[rayonIndex].rayon_id,
                index: rayonIndex
            };

            this.select_subrayon = -1;
            this.select_rayon = -1;
            this.select_etagere = -1;
        },
        deleteSousRayon (tab, rayonIndex, subRayonIndex) {
            this.obj_delete.type = 'Sous-Rayon';
            this.obj_delete.name =
                'Voulez-vous réellement effacer le Sous-Rayon : "' + tab.sous_rayons[subRayonIndex].sous_rayon_nom + '".';
            this.obj_delete.message = 'Cela entraînera la suppression de tout le contenu de ce dernier.';
            this.obj_delete.arg = {
                arg_commit: {
                    rayon_id: tab.rayon_id,
                    sous_rayon_id: tab.sous_rayons[subRayonIndex].sous_rayon_id
                },
                index: subRayonIndex,
                rayon_index: rayonIndex
            };

            this.select_subrayon = -1;
            this.select_etagere = -1;
        },
        deleteEtagere (tab, rayonIndex, subRayonIndex, etagereIndex) {
            this.obj_delete.type = 'Etagere';
            this.obj_delete.name =
                'Voulez-vous réellement effacer l\'étagère : "' + tab.etageres[etagereIndex].etagere_nom + '".';
            this.obj_delete.message = 'Cela entraînera la suppression de tout le contenu de ce dernier.';
            this.obj_delete.arg = {
                arg_commit: {
                    rayon_id: this.Rayons[rayonIndex].rayon_id,
                    sous_rayon_id: tab.sous_rayon_id,
                    etagere_id: tab.etageres[etagereIndex].etagere_id
                },
                index: etagereIndex,
                rayon_index: rayonIndex,
                sous_rayon_index: subRayonIndex
            };

            this.select_etagere = -1;
        },
        /**
         * Edit etagere produits
         */
        editeEtagere (rayonIndex, subRayonIndex, etagereIndex) {
            this.$store.commit('SET_RAYONS_ID',
                this.Rayons[rayonIndex].rayon_id);

            this.$store.commit('SET_SOUS_RAYONS_ID',
                this.Rayons[rayonIndex].sous_rayons[subRayonIndex].sous_rayon_id);

            this.$store.commit('SET_RAYONS_NOM',
                this.Rayons[rayonIndex].rayon_nom);

            this.$store.commit('SET_SOUS_RAYONS_NOM',
                this.Rayons[rayonIndex].sous_rayons[subRayonIndex].sous_rayon_nom);

            this.$store.commit('SET_ETAGERE_ID',
                this.Rayons[rayonIndex].sous_rayons[subRayonIndex].etageres[etagereIndex].etagere_id);

            this.$store.commit('SET_ETAGERE_NOM',
                this.Rayons[rayonIndex].sous_rayons[subRayonIndex].etageres[etagereIndex].etagere_nom);

            this.Rayons = this.$store.getters.getRayonsDetails;

            if (this.Rayons[rayonIndex].sous_rayons[subRayonIndex].etageres[etagereIndex].produit.length > 0) {
                this.$store.commit('SET_PRODUITS_ETAGERE',
                    this.Rayons[rayonIndex].sous_rayons[subRayonIndex].etageres[etagereIndex].produit);
            } else {
                this.$store.commit('SET_TAB_ETAGERE',
                    this.Rayons[rayonIndex].sous_rayons[subRayonIndex].etageres[etagereIndex].fondEcran.positions_produits);
            }

            this.subPage = 'shelve';
            this.Rayons = [];
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.btn-add-shelf {
    @include font-size(1.2);
    background-color: $white;
    border: none;
    color: $primary-color;
    font-weight: 500;
    text-decoration: none;
    i {
        padding-right: 5px;
        vertical-align: middle;
    }
}

.shelves-count {
    border-bottom: 1px solid $grey;
    margin-bottom: 15px;
    p {
        @include font-size(1.4);
        font-weight: 500;
        i {
            padding-right: 10px;
        }
    }
}

.sub-shelves-count {
    border-bottom: 1px solid $grey;
    margin-bottom: 15px;
    p {
        @include font-size(1.4);
        font-weight: 500;
        i {
            padding-right: 10px;
        }
    }
}

.shelf-name {
    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
    background-color: $light-grey;
    border: 1px solid $grey;
    border-radius: 5px;
    padding-top: 10px;
    margin-bottom: 10px;
    input {
        @include font-size(1.4);
        background-color: inherit;
        border: none;
        cursor: pointer;
        font-weight: 500;

        &:focus {
            border-bottom: 1px solid $grey;
        }
    }
    p {
        @include font-size(1.3);
        font-weight: 300;
    }
    button {
        background-color: inherit;
        border: none;
        text-decoration: none;
    }
}

.sub-shelf-name {
    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
    background-color: $lighter-grey;
    border: 1px solid $grey;
    border-radius: 5px;
    padding-top: 10px;
    margin-bottom: 10px;
    input {
        @include font-size(1.4);
        background-color: inherit;
        border: none;
        cursor: pointer;
        font-weight: 500;

        &:focus {
            border-bottom: 1px solid $grey;
        }
    }
    p {
        @include font-size(1.3);
        font-weight: 300;
    }
    button {
        background-color: inherit;
        border: none;
        text-decoration: none;
    }
}

.row-name {
    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
    background-color: $white;
    border: 1px solid $grey;
    border-radius: 5px;
    padding-top: 10px;
    margin-bottom: 10px;
    margin-left: 30px;
    input {
        @include font-size(1.4);
        background-color: inherit;
        border: none;
        cursor: pointer;
        font-weight: 500;

        &:focus {
            border-bottom: 1px solid $grey;
        }
    }
    p {
        @include font-size(1.3);
        font-weight: 300;
    }
    button {
        @include prefix(border-radius, 10px, webkit moz ms o);
        background-color: inherit;
        border: 2px solid $grey;
        font-weight: 500;
        margin-bottom: 5px;
        text-decoration: none;
        &:hover {
            @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
            background-color: $light-grey;
        }
    }
}

.btn-delete-shelf {
    button {
        background-color: inherit;
        border: none;
        text-decoration: none;
        position: absolute;
        top: 50%;
        transform: translate(-20%, 40%);
        i {
            color: $red;
        }
    }
}

.btn-add-row {
    @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
    @include font-size(1.3);
    border-radius: 3px;
    background-color: $primary-color;
    margin-bottom: 10px;
    text-decoration: none;
    color: $white;
    i {
        padding-right: 5px;
        color: $white;
    }
    &:hover {
        @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
        color: $white;
    }
}
</style>
