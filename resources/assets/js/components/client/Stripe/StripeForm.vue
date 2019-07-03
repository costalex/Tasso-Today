<template>
    <form
        id="payment-form"
        action="/checkout"
        method="POST"
        @submit.prevent="pay"
    >
        <div class="col-md-12 col-sm-12 col-xs-12 no-padding form-group">
            <label for="name_on_card">
                Titulaire de la carte
            </label>

            <input
                id="name_on_card"
                name="name_on_card"
                type="text"
                class="form-control"
            >
        </div>

        <div class="col-md-12 col-sm-12 col-xs-12 no-padding form-group">
            <CardElement/>
        </div>

        <input
            :value="csrf"
            type="hidden"
            name="_token"
        >

        <div class="spacer" />

        <div class="col-md-6 col-sm-6 col-xs-6 no-padding">
            <button
                type="submit"
                class="btn btn-save-card"
            >
                Enregistrer
            </button>
        </div>
    </form>
</template>

<script>
import { createToken } from 'vue-stripe-elements-plus';
import CardElement from './StripeCardElement';

export default {
    name: 'StripeForm',
    components: { CardElement },
    data () {
        return {
            csrf: document.head.querySelector('meta[name="csrf-token"]').content,
            name_on_card: ''
        };
    },
    methods: {
        pay () {
            const options = {
                name: this.name_on_card
            };

            createToken(options).then(result => {
                const hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'stripeToken');
                hiddenInput.setAttribute('value', result.token.id);

                this.$store.dispatch('SEND_TOKEN_PAYMENT', result.token.id);
            });
        }
    }
};
</script>

<style lang="scss" scoped>
@import '~sass/_variables';
@import '~sass/_mixins';

.btn-save-card {
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
</style>
