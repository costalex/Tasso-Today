<template>
    <div class="comment-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div
                    class="modal-header"
                    align="right"
                />

                <div v-if="verif_Cart_Pay === 'start'">
                    <div
                        class="loader"
                        align="center"
                    />

                    <p>En attente de paiement</p>
                </div>

                <div v-else-if="verif_Cart_Pay === 'sucess'">
                    <div>
                        <i
                            class="fa fa-check fa-3x"
                            aria-hidden="true"
                        />
                    </div>

                    <p>Paiement accepté</p>
                </div>

                <div v-else-if="verif_Cart_Pay === 'fail'">
                    <div>
                        <i
                            class="fa fa-times fa-3x"
                            aria-hidden="true"
                        />
                    </div>

                    <p>Paiement refusé</p>
                </div>

                <button
                    v-if="verif_Cart_Pay !== 'start'"
                    class="btn btn-success"
                    data-dismiss="modal"
                    @click="endPaymentModal('finish')"
                >
                    Retour
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'PaymentModal',
    data () {
        return {
            commentaire: ''
        };
    },
    computed: {
        ...mapState({
            Aff_card: state => state.ClientOrderModule.aff_card,
            verif_Cart_Pay: state => state.ClientOrderModule.pay_statu
        })
    },
    methods: {
        endPaymentModal (value) {
            this.$store.commit('SET_PAYMENT_STATU', value);
        }
    }
};
</script>
