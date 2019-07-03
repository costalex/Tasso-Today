<template>
    <div id="BoddyCoreCommandeAnnulee">
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
        <div v-if="select_name">
            <p>Votre recherche: </p>

            <div
                v-for="element in commande_search"
                :key="element.id"
            >
                <div v-if="element.statut === 'ANNULE'">
                    <div
                        id="TopCommande"
                        class="col-md-12 col-sm-12 col-xs-12 head-order"
                    >
                        <div class="col-md-3 col-sm-3 col-xs-3 no-padding" />

                        <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                            <p>
                                Reçue le :
                                <span>{{ dateToString(element.created_at, true) }}</span>
                            </p>
                        </div>

                        <div
                            class="col-md-3 col-sm-3 col-xs-3 no-padding"
                            style="text-align: center;"
                        >
                            <p>
                                Référence commande :
                                <span>{{ element.num_commande }}</span>
                            </p>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                            <button @click="downloadRepport(element.num_commande)">
                                Reçu d'annulation
                            </button>
                        </div>
                    </div>

                    <div id="CoreCommande">
                        <div class="col-md-12 col-sm-12 col-xs-12 preview-order">
                            <div
                                id="order-date-deliverySearch"
                                class="col-md-4 col-sm-4 col-xs-4"
                            >
                                <p>A livrer pour :</p>
                                <p>{{ dateToString(element.bon_commande.date_livraison, false) }}</p>
                            </div>

                            <div
                                id="order-cancel-dateSearch"
                                class="col-md-4 col-sm-4 col-xs-4"
                            >
                                <p>Annulée le :</p>
                                <p>{{ dateToString(element.updated_at, true) }}</p>
                            </div>
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
                <div v-if="element.statut === 'ANNULE'">
                    <div
                        id="TopCommande"
                        class="col-md-12 col-sm-12 col-xs-12 head-order"
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

                        <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                            <p>
                                Reçue le :
                                <span>{{ dateToString(element.created_at, true) }}</span>
                            </p>
                        </div>

                        <div
                            class="col-md-3 col-sm-3 col-xs-3 no-padding"
                            style="text-align: center;"
                        >
                            <p>
                                Référence commande :
                                <span>{{ element.num_commande }}</span>
                            </p>
                        </div>

                        <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                            <button @click="downloadRepport(element.num_commande)">
                                Reçu d'annulation
                            </button>
                        </div>
                    </div>

                    <div id="CoreCommande">
                        <div class="col-md-12 col-sm-12 col-xs-12 preview-order">
                            <div
                                id="order-date-delivery"
                                class="col-md-4 col-sm-4 col-xs-4"
                            >
                                <p>A livrer pour :</p>
                                <p>{{ dateToString(element.bon_commande.date_livraison, false) }}</p>
                            </div>

                            <div
                                id="order-cancel-date"
                                class="col-md-4 col-sm-4 col-xs-4"
                            >
                                <p>Annulée le :</p>
                                <p>{{ dateToString(element.updated_at, true) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'OrderBodyCanceled',
    data: () => {
        return {
            commande_search: [],
            select_detail_commande: ''
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
        }
    },
    watch: {
        select_name () {
            this.commande_search = [];
            let commandes = this.$store.getters.getCommande;
            for (let i in commandes) {
                if (commandes[i].num_commande.indexOf(this.$store.getters.getSelectCommande) > -1 &&
                    this.$store.getters.getSelectCommande !== ''
                ) {
                    this.commande_search.push(commandes[i]);
                }
            }
        }
    },
    beforeDestroy () {
        if (this.TypeAccount === 'Vendeur') {
            this.$store.commit('SET_SELECT_COMMANDE', '');
        }
    },
    methods: {
        downloadRepport (OrderNum) {
            this.$store.commit('DOWLOAD_CANCELED_REPPORT', OrderNum);
        },
        dateToString (Dateget, create) {
            const options = {
                year: 'numeric',
                month: 'numeric',
                day: 'numeric',
                hour: 'numeric',
                minute: 'numeric'
            };

            const date = new Date(Dateget);
            if (!create) {
                date.setHours(date.getHours() - 1);
            }

            return date.toLocaleString(options);
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
</style>
