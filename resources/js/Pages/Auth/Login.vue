<template>
    <base-auth title="Please Sign In">
        <template>
            <v-form @submit.prevent="submit" id="login-form">
                <v-text-field
                    label="Login"
                    data-test="email"
                    name="email"
                    prepend-icon="mdi-account"
                    color="blue darken-5"
                    type="text"
                    v-model="formData.email"
                    :error="hasValidationError('email')"
                    :error-messages="getValidationError('email')"
                    @input="clearValidationError('email')"
                />

                <v-text-field
                    id="password"
                    label="Password"
                    data-test="password"
                    name="password"
                    prepend-icon="mdi-lock"
                    color="blue darken-5"
                    type="password"
                    v-model="formData.password"
                    :error="hasValidationError('password')"
                    :error-messages="getValidationError('password')"
                    @input="clearAllValidationErrors"
                />
            </v-form>
        </template>

        <template v-slot:actions>
            <v-row justify="center">
                <v-col cols="10">
                    <v-btn
                        class="px-4 white--text"
                        width="100%"
                        type="submit"
                        color="blue darken-5"
                        form="login-form"
                        >Login</v-btn
                    >
                </v-col>
                <v-col cols="10">
                    <v-btn width="100%" text @click="redirectToPasswordReset"
                        >Forgot your password?</v-btn
                    >
                </v-col>
                <v-col cols="10">
                    <v-btn
                        class="px-4 white--text"
                        width="100%"
                        color="orange accent-3"
                        @click="redirectToRegister"
                        >New Company Registration</v-btn
                    >
                </v-col>
            </v-row>
        </template>
    </base-auth>
</template>

<script>
import FormValidation from "../../mixins/FormValidation";
import BaseAuth from "../../Shared/BaseAuth";
import Layout from "../../Shared/Layout";

export default {
    layout: Layout,
    components: {
        BaseAuth
    },
    mixins: [FormValidation],
    data() {
        return {
            formData: {
                email: "",
                password: ""
            }
        };
    },
    methods: {
        async submit() {
            this.$inertia.post("/login", {
                email: this.formData.email,
                password: this.formData.password
            });
        },
        redirectToPasswordReset() {
            this.$inertia.visit(this.route("password.request"));
        },
        redirectToRegister() {
            this.$inertia.visit(this.route("register"));
        }
    }
};
</script>
