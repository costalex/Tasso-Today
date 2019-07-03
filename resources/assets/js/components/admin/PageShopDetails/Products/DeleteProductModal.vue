<template>
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button
                        class="btn btn-default"
                        type="button"
                        data-dismiss="modal"
                    >
                        &times;
                    </button>
                </div>

                <div class="modal-body">
                    Voulez-vous supprimer {{ obj.nom }} de l'entreprise ?
                    <br>
                    <br>
                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-default sup-modal-btn"
                        type="button"
                        data-dismiss="modal"
                        @click="deleteproduit"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DeleteObjectModal',
    props: {
        obj: { type: Object, required: true }
    },
    computed: {
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
        deleteproduit () {
            this.obj['have'] = false;
            this.$store.dispatch('SET_DELETE_PRODUIT_ENSEIGNE', this.obj['id']);
        }
    }
};
</script>
