<template>
    <div
        id="StripeCard"
        class="block-stripe-payment"
    >
        <StripeForm v-if="Aff_card && Object.values(Cards).length < 1" />

        <div v-else >
            <div v-if="change_card && Aff_card">
                <div v-if="show_card_list && Send_Token">
                    <div
                        v-for="(card, i) in Cards"
                        :key="i"
                        class="col-md-12 col-sm-12 col-xs-12 no-padding"
                    >
                        <div
                            v-if="i > 0"
                            class="col-md-10 col-sm-10 col-xs-10 no-padding"
                        >
                            <button
                                class="btn btn-choose-card"
                                @click="updateDefaultCard(card)"
                            >
                                {{ card.brand + '  **** **** **** **** ' + card.last4 }}
                            </button>
                        </div>

                        <div
                            v-else
                            class="col-md-10 col-sm-10 col-xs-10 no-padding"
                        >
                            <p>
                                {{ card.brand + '  **** **** **** **** ' + card.last4 + ' (carte par défaut)' }}
                            </p>
                        </div>

                        <div class="col-md-2 col-sm-2 col-xs-2 no-padding">
                            <button
                                class="btn-delete-card"
                                @click="deleteCard(card)"
                            >
                                <i
                                    aria-hidden="true"
                                    class="fa fa-times-circle fa-lg"
                                />
                            </button>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                        <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                            <button
                                class="btn btn-add-card"
                                @click="add_card_pay(true)"
                            >
                                Ajouter une carte
                            </button>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                            <button
                                class="btn btn-cancel-edit"
                                @click="change_card_value(false)"
                            >
                                Annuler
                            </button>
                        </div>
                    </div>
                </div>
                <div v-else-if="!Send_Token">
                    <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
                        <button
                            class="btn btn-cancel-edit"
                            @click="add_card_pay(false)"
                        >
                            Annuler l'ajout de carte
                        </button>
                    </div>

                    <StripeForm/>
                </div>
            </div>

            <div
                v-else-if="Aff_card"
                class="col-md-12 col-sm-12 col-xs-12"
            >
                <div class="col-md-12 col-sm-12 col-xs-12 no-padding">
                    <p>
                        {{ Cards[0].brand }}  **** **** **** **** {{ Cards[0].last4 }}
                    </p>
                </div>

                <div
                    class="col-md-12 col-sm-12 col-xs-12 no-padding"
                    align="center"
                >
                    <button
                        class="btn btn-edit-card"
                        @click="change_card_value(true)"
                    >
                        Modifier
                    </button>
                </div>
            </div>

            <div
                v-else
                class="loader"
            />
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import StripeForm from './StripeForm';

export default {
    name: 'StripeCard',
    components: {
        StripeForm
    },
    data: () => {
        return {
            show_card_list: true,
            change_card: false
        };
    },
    computed: {
        ...mapState({
            Send_Token: state => state.ClientOrderModule.send_token,
            Aff_card: state => state.ClientOrderModule.aff_card,
            Cards: state => state.ClientOrderModule.list_card
        })
    },
    mounted () {
        this.$store.dispatch('GET_LIST_CARD_PAYMENT');
    },
    methods: {
        deleteCard (card) {
            this.$store.dispatch('DELETE_CARD_PAYMENT', card);
        },
        updateDefaultCard (card) {
            this.$store.dispatch('UPDATE_DEFAULT_CARD', card);
            this.change_card_value(false);
            this.change_card = false;
        },
        change_card_value (value) {
            this.change_card = value;

            if (value) {
                this.$store.dispatch('GET_LIST_CARD_PAYMENT');
            }
        },
        add_card_pay (value) {
            this.$store.commit('SET_SEND_TOKEN', !value);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.block-stripe-payment {
    p {
        @include font-size(1.4);
        font-weight: 500;
        margin: 0;
        padding: 5px 0 5px 5px;
    }

    h2 {
        background-color: $lighter-grey;
        border-bottom: 1px solid $grey;
        border-top: 1px solid $grey;
        font-weight: 500;
        margin: 0;
        padding: 10px;
    }

    span {
        color: $red;
        font-weight: 500;
    }

    input {
        @include font-size(1.3);
        @include prefix(border-radius, 5px, moz webkit o ms);
        @include placeholder {
            color: $dark-grey;
        }

        background-color: $light-grey;
        border: 1px solid $grey;
        color: $secondary-color;
        margin: 0 5px 10px 5px;
        padding: 8px;
        width: -moz-available;
        width: -webkit-fill-available;
        width: fill-available;
    }

    .btn-choose-card {
        @include font-size(1.4);
        @include prefix(border-radius, 5px, webkit moz ms o);
        @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
        background-color: transparent;
        border: 1px solid $grey;
        color: $secondary-color;
        cursor: pointer;
        margin: 5px 0 0 5px;
        text-decoration: none;
        vertical-align: middle;

        &:hover {
            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
        }
    }

    .btn-add-card {
        @include font-size(1.3);
        @include prefix(border-radius, 5px, webkit moz ms o);
        @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
        background-color: $pastel-green;
        border: none;
        color: $white;
        cursor: pointer;
        margin: 15px 0 0 5px;
        padding: 5px;
        text-decoration: none;
        vertical-align: middle;

        &:hover {
            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
        }
    }

    .btn-delete-card {
        background-color: transparent;
        border: none;
        color: $grey;
        margin-top: 5px;
        text-decoration: none;

        &:hover {
            color: $red;
        }
    }

    .btn-edit-card {
        @include font-size(1.4);
        @include prefix(border-radius, 5px, webkit moz ms o);
        @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
        background-color: $primary-color;
        border: none;
        color: $white;
        cursor: pointer;
        margin-top: 15px;
        text-decoration: none;

        &:hover {
            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
        }
    }

    .btn-cancel-edit {
        @include font-size(1.3);
        @include prefix(border-radius, 5px, webkit moz ms o);
        @include prefix(box-shadow, $light-shadow-box, webkit moz ms o);
        background-color: $red;
        color: $white;
        margin: 15px 10px 10px 20px;
        padding: 5px;

        &:hover {
            @include prefix(box-shadow, $btn-focus-shadow-box, webkit moz ms o);
        }
    }
}
</style>
