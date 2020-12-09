<template>
    <div id="app">
        <v-row>
            <v-col cols="12">
                <h1>{{stripe_pk}}</h1>
                <card
                    class="stripe-card"
                    :class="{ complete }"
                    :stripe="{stripe_pk}"
                    :options="stripeOptions"
                    @change="complete = $event.complete"
                />
            </v-col>
        </v-row>

        <v-row justify="center">
            <v-col cols="10">
                <v-btn
                    class="mx-4"
                    color="primary darken-2"
                    @click="pay"
                    :disabled="!complete"
                >
                    Pay with credit card
                </v-btn>
            </v-col>
        </v-row>
    </div>
</template>

<script>
// import { stripeKey, stripeOptions } from "./stripeConfig.json";
import { Card, createToken } from "vue-stripe-elements-plus";

export default {
    props: {
        payInfo: {
            type: Object
        },
        stripe_pk: {
            type: String,
            required: true
        }
    },
    data() {
        console.log("stripe_pk", this.stripe_pk)
        return {
            complete: false,
            stripeOptions: {
                // see https://stripe.com/docs/stripe.js#element-options for details
            }
        };
    },

    components: { Card },

    methods: {
        pay() {
            // createToken returns a Promise which resolves in a result object with
            // either a token or an error key.
            // See https://stripe.com/docs/api#tokens for the token object.
            // See https://stripe.com/docs/api#errors for the error object.
            // More general https://stripe.com/docs/stripe.js#stripe-create-token.
            createToken().then(data => {
                console.log(data.token);
                this.payInfo.token = data.token.card.id;
            });
        }
    }
};
</script>

<style>
.stripe-card {
    border: 1px solid grey;
}
.stripe-card.complete {
    border-color: green;
}
</style>
