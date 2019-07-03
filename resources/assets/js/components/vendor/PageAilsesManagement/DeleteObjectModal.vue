<template>
    <div class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal"
                    >
                        X
                    </button>
                </div>

                <div class="modal-body">
                    {{ obj.name }}
                    <br>
                    <br>
                    {{ obj.message }}
                </div>

                <div class="modal-footer">
                    <button
                        v-if="obj.type ==='Rayon'"
                        type="button"
                        class="btn btn-default sup-modal-btn"
                        data-dismiss="modal"
                        @click="deleteRayon"
                    >
                        Supprimer
                    </button>

                    <button
                        v-else-if="obj.type ==='Sous-Rayon'"
                        type="button"
                        class="btn btn-default sup-modal-btn"
                        data-dismiss="modal"
                        @click="deleteSousRayon"
                    >
                        Supprimer
                    </button>

                    <button
                        v-else-if="obj.type ==='Etagere'"
                        type="button"
                        class="btn btn-default sup-modal-btn"
                        data-dismiss="modal"
                        @click="deleteEtagere"
                    >
                        Supprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'DeleteObjectModal',
    props: {
        obj: { type: Object, required: true }
    },
    computed: {
        ...mapGetters([
            'notificationTypes'
        ]),
        Rayons: {
            get () {
                return this.$store.getters.getRayonsDetails;
            }
        }
    },
    methods: {
        deleteRayon () {
            this.$store.dispatch('DELETE_RAYON', this.obj.arg.arg_commit)
                .then(() => {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.SUCCESS,
                        message: 'Le rayon a été supprimé'
                    });
                });

            const rayons = [...this.Rayons];

            rayons.splice(this.obj.arg.index, 1);

            this.$store.dispatch('SET_RAYONS_ORDER', rayons);
        },
        deleteSousRayon () {
            this.$store.dispatch('DELETE_SOUS_RAYON', this.obj.arg.arg_commit)
                .then(() => {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.SUCCESS,
                        message: 'Le sous-rayon a été supprimé'
                    });
                });

            const rayons = [...this.Rayons];

            rayons[this.obj.arg.rayon_index]
                .sous_rayons.splice(this.obj.arg.index, 1);

            this.$store.dispatch('SET_RAYONS_ORDER', rayons);
        },
        deleteEtagere () {
            this.$store.dispatch('DELETE_ETAGERE', this.obj.arg.arg_commit)
                .then(() => {
                    this.$store.dispatch('NOTIFY', {
                        type: this.notificationTypes.SUCCESS,
                        message: 'L\'étagère a été supprimée'
                    });
                });

            const rayons = [...this.Rayons];

            rayons[this.obj.arg.rayon_index]
                .sous_rayons[this.obj.arg.sous_rayon_index]
                .etageres.splice(this.obj.arg.index, 1);

            this.$store.dispatch('SET_RAYONS_ORDER', rayons);
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';

.sup-modal-btn {
    background-color: $red;
    color: $white;
}
</style>
