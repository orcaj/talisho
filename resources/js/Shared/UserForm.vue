<template>
    <base-form :title="user.isRegistered ? 'Edit Profile' : 'Set Profile'">
        <template>
            <v-form @submit.prevent="submit" id="set-profile">
                <v-col>
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
                            label="Mobile Phone"
                            data-test="mobile_phone"
                            name="mobile_phone"
                            type="text"
                            placeholder="xxx-xxx-xxxx"
                            v-model="formData.mobile_phone"
                            :error="hasValidationError('mobile_phone')"
                            :error-messages="getValidationError('mobile_phone')"
                            @input="clearValidationError('mobile_phone')"
                    />

                    <v-text-field
                            label="Company Name"
                            data-test="company_name"
                            name="company_name"
                            type="text"
                            v-model="formData.company_name"
                            :error="hasValidationError('company_name')"
                            :error-messages="getValidationError('company_name')"
                            @input="clearValidationError('company_name')"
                    />

                    <v-text-field
                            label="Company Address1"
                            data-test="company_address_1"
                            name="company_address_1"
                            type="text"
                            :rules="requireWithMaxLength('Company Address1', 255)"
                            v-model="formData.company_address_1"
                            :error="hasValidationError('company_address_1')"
                            :error-messages="getValidationError('company_address_1')"
                            @input="clearValidationError('company_address_1')"
                    />


                    <v-text-field
                            label="Company Address2"
                            data-test="company_address_2"
                            name="company_address_2"
                            type="text"
                            :rules="requireWithMaxLength('Company Address2', 255)"
                            v-model="formData.company_address_2"
                            :error="hasValidationError('company_address_2')"
                            :error-messages="getValidationError('company_address_2')"
                            @input="clearValidationError('company_address_2')"
                    />

                    <v-text-field
                            label="City"
                            data-test="compnay_city"
                            name="company_city"
                            type="text"
                            :rules="requireWithMaxLength('City', 255)"
                            v-model="formData.company_city"
                            :error="hasValidationError('company_city')"
                            :error-messages="getValidationError('company_city')"
                            @input="clearValidationError('company_city')"
                    />

                    <v-select
                            :items="statesObj"
                            v-model="formData.company_state"
                            :rules="requireWithMaxLength('State', 255)"
                            item-text="name"
                            item-value="id"
                            label="State"
                            placeholder="State"
                            :error="hasValidationError('company_state')"
                            :error-messages="getValidationError('company_state')"
                            @input="clearValidationError('company_state')"
                    ></v-select>

                    <v-text-field
                            label="Zip Code"
                            data-test="company_zip"
                            name="company_zip"
                            type="text"
                            placeholder="xxxxx"
                            v-model="formData.company_zip"
                            :error="hasValidationError('company_zip')"
                            :error-messages="getValidationError('company_zip')"
                            @input="clearValidationError('company_zip')"
                    />

                    <v-text-field
                            v-if="user.isRegistered"
                            label="Old Password"
                            data-test="old_password"
                            name="old_password"
                            type="password"
                            v-model="formData.old_password"
                            :error="hasValidationError('old_password')"
                            :error-messages="getValidationError('old_password')"
                            @input="clearValidationError('old_password')"
                    />

                    <v-text-field
                            label="Password"
                            data-test="password"
                            name="password"
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
                            type="password"
                            v-model="formData.password_confirmation"
                    />





                </v-col>
            </v-form>
        </template>

        <template v-slot:actions>
            <v-row justify="center" class="my-4">
                <v-btn type="submit" class="mx-2 white--text" color="blue darken-5" form="set-profile">
                    {{ user.isRegistered ? 'Save Changes' : 'Save Information and Login'}}
                </v-btn>
                <inertia-link class="no-underline" :href="route('home')">
                    <v-btn v-if="user.isRegistered" text class="mx-2">Cancel</v-btn>
                </inertia-link>
            </v-row>
        </template>

        <slot></slot>
    </base-form>
</template>

<script>
    import FormValidation from "../mixins/FormValidation";
    import BaseForm from "./BaseForm";
    import states from '../../data/states';

    export default {
        components: {
            BaseForm,
        },
        mixins: [
            FormValidation,
        ],
        props: {
            user: {
                type: Object,
                required: true
            },
            company: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                formData: {
                    email: this.user.email,
                    first_name: this.user.first_name,
                    last_name: this.user.last_name,
                    old_password: '',
                    password: '',
                    password_confirmation: '',
                    mobile_phone: this.user.mobile_phone,
                    company_name: this.company.name,
                    company_address_1: this.company.address_1,
                    company_address_2: this.company.address_2,
                    company_city: this.company.city,
                    company_state: this.company.state,
                    company_zip: this.company.zip,

                },
            }
        },
        created() {
        },
        methods: {
            submit() {
                this.$emit('submit', this.stripEmptyPasswords(this.formData));
            },
            stripEmptyPasswords(obj) {
                if (obj.password === '' && obj.old_password === '' && obj.password_confirmation === '') {
                    let {password, old_password, password_confirmation, ...dataToSubmit} = obj;
                    return dataToSubmit;
                }

                return obj;
            },
            requireWithMaxLength(field, length) {
                return [
                    val => (val || '').length <= length || `${field} must be ${length} characters or less`,
                ]
            },
        },

        computed: {
            statesObj() {
                return Object.entries(states).map(([key, value]) => ({id: key, name: value}));
            },
        },
    }
</script>

