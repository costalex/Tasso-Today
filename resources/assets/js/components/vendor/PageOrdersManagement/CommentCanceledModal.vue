<template>
    <div class="comment-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-xs-12">
                        <div align="left">
                            <p>Dites nous pourquoi vous refusez la commande :</p>
                        </div>

                        <textarea
                            v-model="commentaire"
                            rows="2"
                            style="resize: none; width: 80%;"
                        />

                        <button
                            class="btn btn-cancel-order"
                            style="background-color: red; color: white"
                            data-dismiss="modal"
                            @click="updateStatus('ANNULE')"
                        >
                            Valider l'annulation de la commande
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CommentCanceledModal',
    props: {
        num_commande: { type: String, required: true }
    },
    data: function () {
        return {
            commentaire: ''
        };
    },
    methods: {
        updateStatus (statut) {
            this.$store.dispatch('UPDATE_COMMANDE_STATUT', {
                statut: statut,
                num_commande: this.num_commande,
                commentCancel: this.commentaire
            });
        }
    }
};
</script>

<style lang="scss" scoped>
.comment-modal {
    textarea {
        max-width: 100%;
        min-width: 80%;
        resize: none;
    }
}
</style>
