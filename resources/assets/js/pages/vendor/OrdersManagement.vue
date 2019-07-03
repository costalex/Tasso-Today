<template>
    <div id="BoddyCommandeVendeur">
        <h1>{{ enseigne_name }}</h1>

        <div id="HeadEnseingeDetails">
            <ul class="nav nav-tabs">
                <li
                    id="Commandes"
                    :class="{'blue-menu-selected': SubTab === 'Commandes' }"
                    href="#"
                    @click="makeSubTab('Commandes')"
                >
                    Nouvelle(s) commande(s)
                </li>

                <li
                    id="En cours"
                    :class="{'blue-menu-selected': SubTab === 'En cours' }"
                    href="#"
                    @click="makeSubTab('En cours')"
                >
                    En Préparation
                </li>

                <li
                    id="En livraison"
                    :class="{'blue-menu-selected': SubTab === 'Livraison' }"
                    href="#"
                    @click="makeSubTab('Livraison')"
                >
                    En Livraison
                </li>

                <li
                    id="Terminées"
                    :class="{'blue-menu-selected': SubTab === 'Terminées' }"
                    href="#"
                    @click="makeSubTab('Terminées')"
                >
                    Terminées
                </li>

                <li
                    id="Annulées"
                    :class="{'blue-menu-selected': SubTab === 'Annulées' }"
                    href="#"
                    @click="makeSubTab('Annulées')"
                >
                    Annulées
                </li>
            </ul>
        </div>

        <div
            v-if="SubTab === 'Commandes'"
            id="CoreCommande"
        >
            <Commande/>
        </div>

        <div
            v-if="SubTab === 'En cours'"
            id="CoreCommandeEncours"
        >
            <CommandeEncours/>
        </div>

        <div
            v-if="SubTab === 'Livraison'"
            id="CoreCommandeEnLivraison"
        >
            <CommandeEnLivraison/>
        </div>

        <div
            v-if="SubTab === 'Terminées'"
            id="CoreCommandeTerminees"
        >
            <CommandeTerminer/>
        </div>

        <div
            v-if="SubTab === 'Annulées'"
            id="CoreCommandeAnnulees"
        >
            <CommandeAnnulee/>
        </div>

        <notifications group="foo" />
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Commande from '@/components/vendor/PageOrdersManagement/OrderBody';
import CommandeEncours from '@/components/vendor/PageOrdersManagement/OrderBodyUnderway';
import CommandeTerminer from '@/components/vendor/PageOrdersManagement/OrderBodyTerminated';
import CommandeAnnulee from '@/components/vendor/PageOrdersManagement/OrderBodyCanceled';
import CommandeEnLivraison from '@/components/vendor/PageOrdersManagement/OrderBodyDelivery';

export default {
    name: 'OrderManagement',
    components: {
        Commande,
        CommandeEncours,
        CommandeTerminer,
        CommandeAnnulee,
        CommandeEnLivraison
    },
    data: () => {
        return {
            SubTab: 'Commandes'
        };
    },
    computed: {
        ...mapState({
            TypeAccount: state => state.GeneralModule.TypeUser
        }),
        enseigne_name: {
            get () {
                return this.$store.getters.getEnseingeName;
            }
        }
    },
    methods: {
        makeSubTab (vactive) {
            this.SubTab = vactive;
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.nav {
    border: 0;
    margin-bottom: 15px;

    li {
        @include font-size(1.3);
        color: $secondary-color;
        cursor: pointer;
        font-family: $default-font-family;
        font-weight: 500;
        padding-right: 30px;

        &:active, &:hover {
            color: $primary-color;
            font-weight: 500;
        }
    }
}
</style>
