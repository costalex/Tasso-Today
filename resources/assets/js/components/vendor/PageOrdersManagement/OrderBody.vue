<template>
    <div id="BoddyCoreCommande">
        <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
            <div class="col-md-5 col-sm-5 col-xs-5 no-padding">
                <form id="search-field">
                    <i class="glyphicon glyphicon-search" />
                    <input
                        id="search-input"
                        v-model="select_name"
                        type="text"
                        placeholder="Rechercher commande"
                        name="query"
                    >
                </form>
            </div>

            <div class="col-md-2 col-sm-2 col-xs-2 no-padding">
                <p class="text-waiting-order">
                    {{ num_attente }}
                    <span>en attente</span>
                </p>
            </div>
        </div>

        <div v-if="select_name">
            <p>Votre recherche: </p>
            <div
                v-for="element in commande_search"
                :key="element.id"
            >
                <div v-if="element.statut === 'EN_ATTENTE'">
                    <div
                        id="TopCommande"
                        class="col-md-12 col-sm-12 col-xs-12 head-order"
                    >
                        <div class="col-md-2 col-sm-2 col-xs-2 no-padding">
                            <p v-if="element.bon_commande.livraison">
                                LIVRAISON
                            </p>
                            <p v-else>
                                RETRAIT
                            </p>
                        </div>

                        <div
                            class="col-md-1 col-sm-1 col-xs-1 no-padding"
                            align="center"
                        >
                            <img
                                id="icon-comment"
                                data-target="#ModalCommentCommande"
                                data-toggle="modal"
                                src="/storage/bobby_images/marketplace-client/icon-comment.svg"
                                @click="modalOpenComment(element)"
                            >
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                            <p>
                                Reçue le :<span>{{ DateToString(element.created_at, true) }}</span>
                            </p>
                        </div>

                        <div
                            class="col-md-3 col-sm-3 col-xs-3 no-padding"
                            style="text-align: center;"
                        >
                            <p>Reference commande: <span>{{ element.num_commande }}</span></p>
                        </div>

                        <div
                            class="col-md-3 col-sm-3 col-xs-3 no-padding"
                            align="right"
                        >
                            <button
                                class="btn btn-cancel-order"
                                data-toggle="modal"
                                data-target="#ModalConceledCommande"
                                @click="modalOpen(element)"
                            >
                                ANNULER
                            </button>

                            <button
                                class="btn btn-accept-order"
                                @click="UpdateStatu(element, 'ACCEPTER')"
                            >
                                ACCEPTER
                            </button>
                        </div>
                    </div>

                    <div
                        id="CoreCommande"
                        class="body-order"
                    >
                        <div class="col-md-12 col-sm-12 col-xs-12 preview-order">
                            <div
                                id="order-date-delivery"
                                class="col-md-3 col-sm-3 col-xs-3"
                            >
                                <p>A livrer pour :</p>
                                <p>{{ DateToString(element.bon_commande.date_livraison, false) }}</p>
                            </div>

                            <div
                                id="order-products-number"
                                class="col-md-3 col-sm-3 col-xs-3"
                            >
                                <p>Nombre d'articles</p>
                                <p>{{ element.bon_commande.total_produits }}</p>
                            </div>

                            <div
                                id="order-status"
                                class="col-md-3 col-sm-3 col-xs-3"
                            >
                                <p>Statut</p>
                                <p id="order-state">{{ element.statut }}</p>
                            </div>

                            <div
                                id="order-price"
                                class="col-md-2 col-sm-2 col-xs-2"
                            >
                                <p v-if="!element.bon_commande.livraison">
                                    {{ (element.bon_commande.total_facture * 1).toFixed(2).replace('.', ',') }} €
                                </p>
                                <p v-else>
                                    {{ ((element.bon_commande.total_facture - 2.9) * 1).toFixed(2).replace('.', ',') }} €
                                </p>
                            </div>

                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <button @click="SelectCommande(element.id)">
                                    <i
                                        class="fa fa-chevron-right"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="select_detail_commande === element.id">
                        <Tableau
                            :typeligne="typeligne"
                            :data="element.bon_commande.list_produits"
                            :columns="gridColumns"
                            :filter-key="select_name"
                            class="table-responsive-vendor"
                        />

                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding client-contact">
                            <p>
                                Contact client:
                                {{ element.bon_commande.client_infos.nom }}
                                {{ element.bon_commande.client_infos.telephone }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <template v-else>
            <div
                v-for="element in commande"
                :key="element.id"
            >
                <div v-if="element.statut === 'EN_ATTENTE'">
                    <div
                        id="TopCommande"
                        class="col-xs-12 head-order"
                    >
                        <div class="col-xs-1 no-padding">
                            <p
                                v-if="element.bon_commande.livraison"
                                class="delivery-type"
                            >
                                LIVRAISON
                            </p>

                            <p
                                v-else
                                class="delivery-type"
                            >
                                RETRAIT
                            </p>
                        </div>

                        <div class="col-xs-2 no-padding">
                            <img
                                id="icon-comment"
                                data-target="#ModalCommentCommande"
                                data-toggle="modal"
                                src="/storage/bobby_images/marketplace-client/icon-comment.svg"
                                @click="modalOpenComment(element)"
                            >
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                            <p>
                                Reçue le : <span>{{ DateToString(element.created_at) }}</span>
                            </p>
                        </div>

                        <div
                            class="col-md-3 col-sm-3 col-xs-3 no-padding"
                            style="text-align: center;"
                        >
                            <p>
                                Reference commande: <span>{{ element.num_commande }}</span>
                            </p>
                        </div>

                        <div
                            class="col-md-3 col-sm-3 col-xs-3 no-padding"
                            align="right"
                        >
                            <button
                                class="btn btn-cancel-order"
                                data-toggle="modal"
                                data-target="#ModalConceledCommande"
                                @click="modalOpen(element)"
                            >
                                ANNULER
                            </button>

                            <button
                                class="btn btn-accept-order"
                                @click="UpdateStatu(element, 'ACCEPTER')"
                            >
                                ACCEPTER
                            </button>
                        </div>
                    </div>

                    <div
                        id="CoreCommande"
                        class="body-order"
                    >
                        <div class="col-md-12 col-sm-12 col-xs-12 preview-order">
                            <div
                                id="order-date-delivery"
                                class="col-md-3 col-sm-3 col-xs-3"
                            >
                                <p>A livrer pour :</p>
                                <p>{{ DateToString(element.bon_commande.date_livraison, false) }}</p>
                            </div>

                            <div
                                id="order-products-number"
                                class="col-md-3 col-sm-3 col-xs-3"
                            >
                                <p>Nombre d'articles</p>
                                <p>{{ element.bon_commande.total_produits }}</p>
                            </div>

                            <div
                                id="order-status"
                                class="col-md-3 col-sm-3 col-xs-3"
                            >
                                <p>Statut</p>
                                <p id="order-state">
                                    {{ element.statut }}
                                </p>
                            </div>

                            <div
                                id="order-price"
                                class="col-md-2 col-sm-2 col-xs-2"
                            >
                                <p v-if="!element.bon_commande.livraison">
                                    {{ (element.bon_commande.total_facture * 1).toFixed(2).replace('.', ',') }} €
                                </p>
                                <p v-else>
                                    {{ ((element.bon_commande.total_facture - 2.9) * 1).toFixed(2).replace('.', ',') }} €
                                </p>
                            </div>

                            <div class="col-md-1 col-sm-1 col-xs-1">
                                <button @click="SelectCommande(element.id)">
                                    <i
                                        class="fa fa-chevron-right"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>

                    <div v-if="select_detail_commande === element.id">
                        <Tableau
                            :typeligne="typeligne"
                            :data="element.bon_commande.list_produits"
                            :columns="gridColumns"
                            :filter-key="select_name"
                            class="table-responsive-vendor"
                        />

                        <div class="col-md-12 col-sm-12 col-xs-12 no-padding client-contact">
                            <p>
                                Contact client:
                                {{ element.bon_commande.client_infos.nom }}
                                {{ element.bon_commande.client_infos.telephone }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <ModalCommentCommande
            v-show="showModalComment"
            id="ModalCommentCommande"
            :command="order"
            class="modal fade"
            role="dialog"
        />

        <ModalConcelCommande
            v-show="showModal"
            id="ModalConceledCommande"
            :num_commande="num_order"
            class="modal fade"
            role="dialog"
        />
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Tableau from './OrderTable';
import ModalCommentCommande from './CommentModal';
import ModalConcelCommande from './CommentCanceledModal';

export default {
    name: 'OrderBody',
    components: {
        Tableau,
        ModalConcelCommande,
        ModalCommentCommande
    },
    data: () => {
        return {
            order: '',
            num_order: '',
            showModal: false,
            showModalComment: false,
            commande_search: [],
            typeligne: 'Commande',
            gridColumns: [
                { key: 'path_file_photo_principale', value: '' },
                { key: 'nom', value: 'Nom produit' },
                { key: 'stocks', value: 'Modèle' },
                { key: 'couleur', value: 'Couleur' },
                { key: 'quantite', value: 'Quantité' },
                { key: 'prix', value: 'Prix' }
            ],
            select_detail_commande: '',
            audio: Audio
        };
    },
    computed: {
        ...mapState({
            TypeAccount: state => state.GeneralModule.TypeUser
        }),
        select_name: {
            set (value) {
                this.$store.commit('SET_SELECT_COMMANDE', value);
            },
            get () {
                return this.$store.getters.getSelectCommande;
            }
        },
        commande: {
            get () {
                return this.$store.getters.getCommande;
            }
        },
        num_attente: {
            get () {
                let numAttente =
                    this.$store.getters.getCommande.filter(x =>
                        x.statut === 'EN_ATTENTE'
                    ).length;

                return numAttente;
            }
        }
    },
    watch: {
        select_name () {
            this.commande_search = [];

            let commandes = this.$store.getters.getCommande;
            for (let i = 0; i < commandes.length; i++) {
                if (commandes[i].num_commande.indexOf(this.$store.getters.getSelectCommande) > -1 &&
                    this.$store.getters.getSelectCommande !== ''
                ) {
                    this.commande_search.push(commandes[i]);
                }
            }
        }
    },
    beforeMount () {
        this.intervalid1 = setInterval(() => {
            this.Order_wait();
        }, 3000);
    },
    beforeDestroy () {
        if (this.audio) {
            this.audio.src = '';
        }

        clearInterval(this.intervalid1);

        if (this.TypeAccount === 'Vendeur') {
            this.$store.commit('SET_SELECT_COMMANDE', '');
        }
    },
    methods: {
        DateToString (Dateget, create) {
            const date = new Date(Dateget);
            if (!create) {
                date.setHours(date.getHours() - 1);
            }

            return date.toLocaleString();
        },
        modalOpen (element) {
            this.num_order = element.num_commande;
            this.showModal = true;
        },
        modalOpenComment (element) {
            this.order = element;
            this.showModalComment = true;
        },
        UpdateStatu (commande, statut) {
            this.$store.dispatch('UPDATE_COMMANDE_STATUT', {
                statut: statut,
                num_commande: commande.num_commande,
                commentCancel: ''
            });
        },
        SelectCommande (id) {
            if (this.select_detail_commande === id) {
                this.select_detail_commande = '';
            } else {
                this.select_detail_commande = id;
            }
        },
        Order_wait () {
            if (this.$store.getters.getCommande) {
                const commandes = this.$store.getters.getCommande;
                for (const property in commandes) {
                    if (commandes.hasOwnProperty(property) && commandes[property].statut === 'EN_ATTENTE') {
                        this.audio = new Audio('/storage/sound/notify/to-the-point-small.mp3');
                        this.audio.play();
                    }
                }
            }
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

#search-field {
    position: relative;
    .glyphicon-search {
        color: #666;
        left: 10px;
        position: absolute;
        top: 10px;
    }
    #search-input {
        @include font-size(1.3);
        border: 1px solid $grey;
        border-radius: 5px;
        margin-right: 10px;
        padding: 5px 0 5px 30px;
        width: stretch;
    }
}

.text-waiting-order {
    @include font-size(1.3);
    color: $secondary-color;
    font-weight: 500;
    margin-bottom: 0;
    margin-top: 5px;
    span {
        color: $red;
    }
}

.head-order {
    background-color: $light-blue;
    border: 1px solid $grey;
    @include prefix(border-radius, 5px 5px 0 0, webkit moz ms o);
    margin-top: 20px;
    p {
        @include font-size(1.3);
        font-weight: 400;
        margin: 10px 0 0 0;
        span {
            font-weight: 500;
        }
    }
    .delivery-type {
        color: $primary-color;
        font-weight: 500;
    }

    #icon-comment {
        margin-top: 10px;
        padding-left: 8px;
        &:hover {
            content: url('/storage/bobby_images/marketplace-client/icon-comment-hover.svg');
        }
    }

    button {
        @include font-size(1.2);
        @include prefix(box-shadow, $btn-shadow-box, webkit moz ms o);
        border-radius: 3px;
        color: $white;
        font-weight: 500;
        margin: 10px 0 10px 0;
        padding: 3px 5px 3px 5px;
        width: 80px;
        &:hover {
            color: $white;
            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
        }
    }
    .btn-validate-order {
        background-color: $primary-color;
    }

    .btn-cancel-order {
        background-color: $red;
        margin-right: 10px;
    }

    .btn-accept-order {
        background-color: $green;
    }
}

.preview-order {
    @include prefix(box-shadow, $lighter-shadow-box, webkit moz ms o);
    border-bottom: 1px solid $grey;
    border-left: 1px solid $grey;
    border-right: 1px solid $grey;
    border-radius: 0 0 3px 3px;
    #order-date-delivery {
        padding: 5px;
        p {
            @include font-size(1.3);
            font-weight: 400;
            margin: 0;
        }
    }

    #order-products-number {
        padding: 5px;
        text-align: center;
        p {
            @include font-size(1.3);
            font-weight: 400;
            margin: 0;
        }
    }

    #order-status {
        padding: 5px;
        text-align: center;
        p {
            @include font-size(1.3);
            font-weight: 400;
            margin: 0;
            &:last-child {
                color: $primary-color;
                font-weight: 500;
            }
        }
    }
    button {
        border: none;
        background: none;
        margin-top: 10px;
    }

    #order-price {
        padding: 10px 5px 5px 5px;
        position: relative;
        text-align: center;
        p {
            @include font-size(1.7);
            font-weight: 500;
            margin: 0;
        }
    }

    #order-cancel-date {
        padding: 5px;
        p {
            @include font-size(1.3);
            font-weight: 400;
            margin: 0;
        }
    }
}

.client-contact {
    @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
    border-bottom: 1px solid $grey;
    border-left: 1px solid $grey;
    border-right: 1px solid $grey;
    border-radius: 0 0 5px 5px;
    padding: 10px 0 0 15px;
    p {
        @include font-size(1.3);
        font-weight: 500;
    }
}
</style>
