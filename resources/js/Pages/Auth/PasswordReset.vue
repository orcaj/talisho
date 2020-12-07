<template>
    <base-auth title="Reset Password">
        <template>
            <v-form @submit.prevent="submit" id="reset-password">
                <v-input type="hidden" v-model="formData.token"></v-input>

                <v-text-field
                    label="Email"
                    data-test="email"
                    name="email"
                    prepend-icon="mdi-account"
                    type="text"
                    v-model="formData.email"
                    :error="hasValidationError('email')"
                    :error-messages="getValidationError('email')"
                    @input="clearValidationError('email')"
                />

                <v-text-field
                    label="Password"
                    data-test="password"
                    name="password"
                    prepend-icon="mdi-lock"
                    type="password"
                    v-model="formData.password"
                    :error="hasValidationError('password')"
                    :error-messages="getValidationError('password')"
                    @input="clearValidationError('password')"
                />

                <v-text-field
                    label="Confirm Password"
                    data-test="password_confirmation"
                    name="password_confirmation"
                    prepend-icon="mdi-lock"
                    type="password"
                    v-model="formData.password_confirmation"
                />
            </v-form>
        </template>
        <template v-slot:actions>
            <v-spacer />
            <v-btn
                type="submit"
                class="mr-4"
                color="primary darken-2"
                form="reset-password"
                >Reset Password</v-btn
            >
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
    props: {
        email: {
            type: String,
            required: true
        },
        token: {
            type: String,
            required: true
        }
    },
    data() {
        return {
            formData: {
                email: "",
                password: "",
                password_confirmation: "",
                token: ""
            }
        };
    },
    created() {
        this.formData.email = this.email;
        this.formData.token = this.token;
    },
    methods: {
        async submit() {
            this.$inertia.post(this.route("password.update"), this.formData);
        }
    }
};
</script>
