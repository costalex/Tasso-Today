<template>
    <div class="comment-modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div
                    class="modal-header"
                    align="right"
                >
                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal"
                    >
                        <img src="/storage/bobby_images/marketplace-client/icon-cross.svg">
                    </button>
                </div>

                <p>Laissez un commentaire au commerçant : </p>

                <textarea
                    v-model="commentaire"
                    :placeholder="placeholder"
                    class="textarea-comment"
                />

                <br>

                <button
                    class="btn btn-save-comment"
                    data-dismiss="modal"
                >
                    Enregistrer
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'CommentModal',
    props: {
        shopName: { type: String, required: true }
    },
    data () {
        return {
            commentaire: '',
            placeholder: 'Un bouquet de fleur ? Précisez ici si vous souhaitez écrire un mot. Signalez ' +
                'vos allergies etc. Ou saluez tout simplement votre commerçant :)'
        };
    },
    watch: {
        commentaire () {
            this.$store.commit('SET_COMMENTAIRE_PANIER_IN_GENERAL', {
                shopname: this.shopName,
                commentaire: this.commentaire
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
