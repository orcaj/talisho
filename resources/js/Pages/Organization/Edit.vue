<template>
    <base-form title="Edit Company Information">
        <template>
            <v-form @submit.prevent="submit" id="edit-org">
                <v-text-field
                    label="Name"
                    data-test="name"
                    name="name"
                    type="text"
                    v-model="formData.name"
                    :error="hasValidationError('name')"
                    :error-messages="getValidationError('name')"
                    @input="clearValidationError('name')"
                />

                <v-text-field
                    label="Address Line 1"
                    data-test="address_1"
                    name="address_1"
                    type="text"
                    v-model="formData.address_1"
                    :error="hasValidationError('address_1')"
                    :error-messages="getValidationError('address_1')"
                    @input="clearValidationError('address_1')"
                />

                <v-text-field
                    label="Address Line 2"
                    data-test="address_2"
                    name="address_2"
                    type="text"
                    v-model="formData.address_2"
                />

                <v-text-field
                    label="City"
                    data-test="city"
                    name="city"
                    type="text"
                    v-model="formData.city"
                    :error="hasValidationError('city')"
                    :error-messages="getValidationError('city')"
                    @input="clearValidationError('city')"
                />

                <v-text-field
                    label="Zip"
                    data-test="zip"
                    name="zip"
                    type="text"
                    v-model="formData.zip"
                    :error="hasValidationError('zip')"
                    :error-messages="getValidationError('zip')"
                    @input="clearValidationError('zip')"
                />

                <v-select
                    :items="statesObj"
                    v-model="formData.state"
                    item-text="name"
                    item-value="id"
                    label="State"
                ></v-select>

                <v-select
                    :items="countriesObj"
                    v-model="formData.country"
                    item-text="name"
                    item-value="id"
                    label="Country"
                ></v-select>

                <v-text-field
                    label="Phone"
                    data-test="phone"
                    name="phone"
                    type="text"
                    v-model="formData.phone"
                    :error="hasValidationError('phone')"
                    :error-messages="getValidationError('phone')"
                    @input="clearValidationError('phone')"
                />

                <v-text-field
                    label="Website"
                    data-test="website"
                    name="website"
                    type="text"
                    v-model="formData.website"
                    :error="hasValidationError('website')"
                    :error-messages="getValidationError('website')"
                    @input="clearValidationError('website')"
                />

                <v-text-field
                    label="Primary Contact Name"
                    data-test="primary_contact_name"
                    name="primary_contact_name"
                    type="text"
                    v-model="formData.primary_contact_name"
                    :error="hasValidationError('primary_contact_name')"
                    :error-messages="getValidationError('primary_contact_name')"
                    @input="clearValidationError('primary_contact_name')"
                />
                <v-text-field
                    label="Primary Contact Email"
                    data-test="primary_contact_email"
                    name="primary_contact_email"
                    type="text"
                    v-model="formData.primary_contact_email"
                    :error="hasValidationError('primary_contact_email')"
                    :error-messages="getValidationError('primary_contact_email')"
                    @input="clearValidationError('primary_contact_email')"
                />
                <v-text-field
                    label="Primary Contact Phone"
                    data-test="primary_contact_phone"
                    name="primary_contact_phone"
                    type="text"
                    placeholder="xxx-xxx-xxxx"
                    v-model="formData.primary_contact_phone"
                    :error="hasValidationError('primary_contact_phone')"
                    :error-messages="getValidationError('primary_contact_phone')"
                    @input="clearValidationError('primary_contact_phone')"
                />

                <v-select
                    :items="disciplines"
                    v-model="formData.disciplines"
                    item-text="label"
                    item-value="id"
                    label="Discipline"
                    multiple
                ></v-select>

                <v-select
                    :items="employeeCounts"
                    v-model="formData.employee_count_id"
                    item-text="label"
                    item-value="id"
                    label="Employee Count"
                ></v-select>
            </v-form>
        </template>
        <template v-slot:actions>
            <v-row justify="center" class="my-4">
                <v-btn type="submit" class="mx-2 white--text" color="blue darken-5" form="edit-org">
                    Update Information
                </v-btn>
                <inertia-link class="no-underline" :href="route('home')">
                    <v-btn text class="mx-2">Cancel</v-btn>
                </inertia-link>
            </v-row>
        </template>
    </base-form>

</template>

<script>
    import FormValidation from "../../mixins/FormValidation";
    import BaseForm from "../../Shared/BaseForm";
    import Layout from "../../Shared/Layout";

    export default {
        layout: Layout,
        components: {
            BaseForm
        },
        mixins: [
            FormValidation,
        ],
        props: {
            organization: {
                type: Object,
                required: true,
            },
            disciplines: {
                type: Array,
                required: true,
            },
            employeeCounts: {
                type: Array,
                required: true
            },
            states: {
                type: Object,
                required: true
            },
            countries: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                formData: {
                    name: this.organization.name,
                    address_1: this.organization.address_1,
                    address_2: this.organization.address_2,
                    city: this.organization.city,
                    state: this.organization.state,
                    zip: this.organization.zip,
                    country: this.organization.country,
                    phone: this.organization.phone,
                    website: this.organization.website,
                    disciplines: [],
                    employee_count_id: this.organization.employee_count_id,
                    primary_contact_name: this.organization.primary_contact_name,
                    primary_contact_email: this.organization.primary_contact_email,
                    primary_contact_phone: this.organization.primary_contact_phone
                },
                showConfirmation: false
            }
        },
        computed: {
            statesObj() {
                return Object.keys(this.states).map(key => ({id: key, name: this.states[key]}))
            },
            countriesObj() {
                return Object.keys(this.countries).map(key => ({id: key, name: this.countries[key]}))
            }
        },
        created() {
            const disciplineIds = this.disciplines.map(({id}) => id);
            this.formData.disciplines = this.organization.disciplines.filter(({id}) => disciplineIds.includes(id)).map(({id}) => id);
        },
        methods: {
            async submit() {
                this.$inertia.put(
                    this.route('organizations.update', {organization: this.organization.id}),
                    this.formData
                );
            }
        }
    }
</script>

