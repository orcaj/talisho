<template>
    <v-card>
        <v-card-title class="mt-2">Invite New User</v-card-title>
        <v-form @submit.prevent="submit" class="p-3" id="invite-user">
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

        <v-card-actions>
            <v-flex class="text-sm-right">
            <v-btn type="submit" class="mx-2 white--text" color="blue darken-5" form="invite-user">INVITE GUEST</v-btn>
                <v-btn
                        color="secondary"
                        text
                        class="mx-0 px-2"
                        @click="$emit('close')"
                >
                    CANCEL
                </v-btn>
            </v-flex>
        </v-card-actions>
    </v-card>
</template>

<script>
    import FormValidation from "../mixins/FormValidation";
    import BaseAuth from "../Shared/BaseAuth";
    export default {
        name: "UserGuestAdd",
        components: {
            BaseAuth
        },
        props: {
            discipline: {
                type: Object,
                required: true
            },

            dialog: {
                type: Boolean,
                required: true
            },
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
                this.formData.discipline  = this.discipline.id;
                this.$inertia.post(this.route('users.store'), this.formData);
                this.$emit('close');
            }
        },
        watch: {
            dialog: function(val) {
                if(val) {
                    this.formData.email = '';
                    this.formData.first_name = '';
                    this.formData.last_name = '';
                }
            }
        }
    }
</script>
