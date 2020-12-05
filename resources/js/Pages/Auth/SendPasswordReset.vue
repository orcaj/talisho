<template>
    <base-auth title="Reset Password">
        <template>
            <v-form @submit.prevent="submit" id="send-reset">
                <v-alert
                    v-if="$page.flash.success"
                    dense
                    text
                    type="success"
                >
                    {{ $page.flash.success }}
                </v-alert>

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
            </v-form>
        </template>
        <template v-slot:actions>
            <v-row justify="center">
                <v-col cols="10">
                    <v-btn width="100%" type="submit" class="mr-4 white--text" color="blue darken-5" form="send-reset">Send Password Reset Link</v-btn>
                </v-col>
                <v-col cols="10">
                    <inertia-link class="no-underline" :href="route('login')">
                        <v-btn width="100%" text >Cancel</v-btn>
                    </inertia-link>
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
        layout: (h, page) => h(Layout, [page]),
        components: {
            BaseAuth
        },
        mixins: [
            FormValidation,
        ],
        data() {
            return {
                formData: {
                    email: ''
                }
            }
        },
        methods: {
            async submit() {
                this.$inertia.post(this.route('password.email'), this.formData);
            }
        }
    }
</script>
