<template>
    <div>
        <card
            :class="{ complete }"
            :options="stripeOptions"
            :stripe="stripeKey"
            class="stripe-card"
            @change="change($event)"
        />

        <div
            id="card-errors"
            role="alert"
            v-text="errorMessage"
        />
    </div>
</template>

<script>
import { Card } from 'vue-stripe-elements-plus';
import env from '@/env';

export default {
    components: { Card },
    data () {
        return {
            complete: false,
            errorMessage: '',
            stripeKey: env.STRIPE_KEY,
            stripeOptions: {
                style: {
                    base: {
                        color: '#32325d',
                        fontFamily: '"Raleway", Helvetica, sans-serif',
                        fontSmoothing: 'antialiased',
                        fontSize: '16px',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                },
                hidePostalCode: true
            }
        };
    },
    methods: {
        change (event) {
            this.errorMessage = event.error ? event.error.message : '';
        }
    }
};
</script>
