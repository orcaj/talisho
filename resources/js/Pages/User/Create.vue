<template>
    <base-auth title="Invite New User">
        <template>
            <v-form @submit.prevent="submit" id="invite-user">
                <v-text-field
                    label="First Name"
                    data-test="first_name"
                    name="first_name"
                    type="text"
                    v-model="formData.first_name"
                    :error="hasValidationError('first_name')"
                    :error-messages="getValidationError('first_name')"
                    @input="clearValidationError('first_name')"
                />

                <v-text-field
                    label="Last Name"
                    data-test="last_name"
                    name="last_name"
                    type="text"
                    v-model="formData.last_name"
                    :error="hasValidationError('last_name')"
                    :error-messages="getValidationError('last_name')"
                    @input="clearValidationError('last_name')"
                />

                <v-text-field
                    label="Email"
                    data-test="email"
                    name="email"
                    type="text"
                    v-model="formData.email"
                    :error="hasValidationError('email')"
                    :error-messages="getValidationError('email')"
                    @input="clearValidationError('email')"
                />
            </v-form>
        </template>
        <template v-slot:actions>
            <v-row justify="center" class="my-4">
                <v-btn type="submit" class="mx-2 white--text" color="blue darken-5" form="invite-user">Send Invite</v-btn>
                <inertia-link class="no-underline" :href="route('organizations.users.index', {organization: $page.auth.user.organization.id})">
                    <v-btn text class="mx-2">Cancel</v-btn>
                </inertia-link>
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
                    email: '',
                    first_name: '',
                    last_name: ''
                }
            }
        },
        methods: {
            submit() {
                this.$inertia.post(this.route('users.store'), this.formData);
            }
        }
    }
</script>
