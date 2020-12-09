<template>
    <base-auth title="Please Sign Up" lgSize="8">
        <v-stepper v-model="currentStep">
            <v-stepper-header>
                <v-stepper-step step="1" editable>
                    Company Information
                </v-stepper-step>

                <v-stepper-step step="2" :editable="companyInfoValid">
                    Primary Contact Information
                </v-stepper-step>

                <v-stepper-step
                    step="3"
                    :editable="companyInfoValid && userInfoValid"
                >
                    Payment Information
                </v-stepper-step>
            </v-stepper-header>

            <v-stepper-content step="1">
                <company
                    :companyInfo.sync="formData.companyInfo"
                    @valid="updateCompanyInfoStatus"
                    @next="next"
                ></company>
            </v-stepper-content>

            <v-stepper-content step="2">
                <user
                    :userInfo.sync="formData.userInfo"
                    @valid="updateUserInfoStatus"
                    @next="next"
                ></user>
            </v-stepper-content>

            <v-stepper-content step="3">
                <div id="app">
                    <v-row>
                        <v-col cols="12">
                            <card
                                class="stripe-card"
                                :class="{ complete }"
                                stripe="{stripe_pk}"
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
                                Pay with credit card And Register
                            </v-btn>
                        </v-col>
                    </v-row>
                </div>

                <!-- <checkout
                    :payInfo.sync="formData.payInfo"
                    @valid="updateUserInfoStatus"
                    @submit="submit"
                >
                </checkout> -->
            </v-stepper-content>
        </v-stepper>
    </base-auth>
</template>

<script>
import FormValidation from "../../mixins/FormValidation";
import BaseAuth from "../../Shared/BaseAuth";
import Layout from "../../Shared/Layout";

import Company from "./RegisterStep/Company";
import User from "./RegisterStep/User";

import Checkout from "./RegisterStep/Checkout";

import { Card, createToken } from "vue-stripe-elements-plus";

export default {
    layout: Layout,
    components: {
        BaseAuth,
        Company,
        User,
        Checkout,
        Card
    },
    props: {
        stripe_pk: {
            type: String,
            required: true
        }
    },
    mixins: [FormValidation],
    data() {
        return {
            // stripe_pk:"",
            complete: false,
            currentStep: 1,
            companyInfoValid: false,
            userInfoValid: false,
            formData: {
                companyInfo: {
                    companyName: "",
                    companyAddress1: "",
                    companyAddress2: "",
                    city: "",
                    state: "",
                    zipCode: ""
                },
                userInfo: {
                    first_name: "",
                    last_name: "",
                    email: "",
                    phone: "",
                    password: "",
                    password_confirmation: ""
                },
                payInfo: {
                    token: "",
                    card_last4: "",
                    card_brand: ""
                }
            },
            stripeOptions: {}
        };
    },
    created() {
    },
    methods: {
        next() {
            this.currentStep = parseInt(this.currentStep) + 1;
        },
        back() {
            this.currentStep = parseInt(this.currentStep) - 1;
        },

        updateCompanyInfoStatus(value) {
            this.companyInfoValid = value;
        },
        updateUserInfoStatus(value) {
            this.userInfoValid = value;
        },
        async submit() {
            this.$inertia.post("/register", {
                formData: this.formData
            });
        },
        pay() {
            this.complete = false;
            // See https://stripe.com/docs/api#tokens for the token object.
            // See https://stripe.com/docs/api#errors for the error object.
            // More general https://stripe.com/docs/stripe.js#stripe-create-token.
            createToken().then(data => {
                console.log("toekn", data.token);
                this.formData.payInfo.token = data.token.id;
                this.formData.payInfo.card_brand = data.token.card.brand;
                this.formData.payInfo.card_last4 = data.token.card.last4;
                this.submit();
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
