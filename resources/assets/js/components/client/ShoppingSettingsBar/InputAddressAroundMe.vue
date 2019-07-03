<template>
    <div class="around-me-subcontainer">
        <div class="around-me-legend-container">
            <p class="around-me-legend">Autour de moi :</p>
        </div>

        <div class="around-me-modify-container">
            <div
                v-if="selectedAddress"
                data-toggle="modal"
                data-target="#ModalAdressBar"
                class="around-me-modify"
            >
                <p>{{ selectedAddress }}</p>
            </div>

            <div
                v-else-if="selectedAddress === ''"
                data-toggle="modal"
                data-target="#ModalAdressBar"
                class="around-me-modify"
            >
                <p>Saisissez votre adresse de livraison</p>
            </div>
        </div>

        <ModalAroundMe
            id="ModalAdressBar"
            class="modal fade"
            role="dialog"
        />
    </div>
</template>

<script>
import ModalAroundMe from './ModalAroundMe';

export default {
    name: 'InputAddressAroundMe',
    components: { ModalAroundMe },
    props: {
        selectedAddress: { type: String, required: true }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';

.around-me-subcontainer {
    width: 100%;
    text-align: left;
    padding: 0 5px 0 10px;
    border-right: 1px solid $grey;

    .around-me-legend {
        margin: 0;
        padding-right: 10px;
        text-transform: uppercase;
        font-weight: 500;
        font-size: 12px;
        color: $primary-color;

    }

    .around-me-modify {
        cursor: pointer;

        p {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
}
</style>
