<template>
    <base-auth title="Please Sign Up" lgSize="8">
        <v-stepper v-model="currentStep">
            <v-stepper-header>
                <v-stepper-step step="1" editable>
                    Company Information
                </v-stepper-step>

                <v-stepper-step step="2" :editable="companyInfoValid">
                    Primary Contact Informationz
                </v-stepper-step>

                <v-stepper-step
                    step="3"
                    :editable="companyInfoValid && userInfoValid"
                >
                    Primary Contact Informationz
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
                asdf
                <v-stripe-card
                    v-model="source"
                    :api-key="process.env.VUE_APP_STRIPE_API_KEY"
                ></v-stripe-card>
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
import { VStripeCard } from 'v-stripe-elements'

export default {
        

    layout: Layout,
    components: {
        BaseAuth,
        Company,
        User,
        VStripeCard
    },
    mixins: [FormValidation],
    data() {
        return {
            source: null,
            currentStep: 3,
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
                }
            }
        };
    },
    created() {
        console.log("vudddde ke created", process.env.VUE_APP_STRIPE_API_KEY)
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
        submit() {
            this.$inertia.visit(this.route("register"), {
                data: this.formData,
                method: "post"
            });
        }
        //   toggleDefault(value) {
        //       this.formData.disciplineData.saveDefaults = value;
        //   },
        //   setSelectedDisciplines(value) {
        //       this.formData.disciplineData.selected = value;
        //   },
        //   updateLead({ newLead, discipline_id }) {
        //       const index = this.formData.disciplineData.selected.findIndex(
        //           item => item.id === discipline_id
        //       );
        //       this.formData.disciplineData.selected[index].lead = newLead;
        //   },
    }
};
</script>
