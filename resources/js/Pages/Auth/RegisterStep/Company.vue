<template>
    <v-form v-model="valid" id="company">
        <v-row justify="center">
            <v-col cols="12">
                <v-text-field
                    v-model="companyInfo.companyName"
                    class="pb-2"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Company Name', 255)"
                    label="Company Name"
                    placeholder="Company Name"
                    :error="hasValidationError('companyInfo.companyName')"
                    :error-messages="
                        getValidationError('companyInfo.companyName')
                    "
                ></v-text-field>

                <v-text-field
                    v-model="companyInfo.companyAddress1"
                    class="pb-2"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Company Address1', 255)"
                    label="Company Address1"
                    placeholder="Company Address1"
                    :error="hasValidationError('companyInfo.companyAddress1')"
                    :error-messages="
                        getValidationError('companyInfo.companyAddress1')
                    "
                ></v-text-field>

                <v-text-field
                    v-model="companyInfo.companyAddress2"
                    class="pb-2"
                    outlined
                    dense
                    label="Company Address2"
                    placeholder="Company Address2"
                    :error="hasValidationError('companyInfo.companyAddress2')"
                    :error-messages="
                        getValidationError('companyInfo.companyAddress2')
                    "
                ></v-text-field>
                <v-row>
                    <v-col cols="8">
                        <v-text-field
                            v-model="companyInfo.city"
                            outlined
                            dense
                            :rules="requireWithMaxLength('City', 255)"
                            label="City"
                            placeholder="City"
                            :error="hasValidationError('companyInfo.city')"
                            :error-messages="
                                getValidationError('companyInfo.city')
                            "
                        ></v-text-field>
                    </v-col>
                    <v-col cols="4">
                        <v-select
                            :items="statesObj"
                            v-model="companyInfo.state"
                            :rules="requireWithMaxLength('State', 255)"
                            item-text="name"
                            item-value="id"
                            outlined
                            dense
                            label="State"
                            placeholder="State"
                            :error="hasValidationError('companyInfo.state')"
                            :error-messages="
                                getValidationError('companyInfo.state')
                            "
                        ></v-select>
                    </v-col>
                </v-row>
                <v-text-field
                    v-model="companyInfo.zipCode"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Zip Code', 255)"
                    label="Zip Code"
                    placeholder="Zip Code"
                    :error="hasValidationError('companyInfo.zipCode')"
                    :error-messages="getValidationError('companyInfo.zipCode')"
                ></v-text-field>
            </v-col>
        </v-row>

        <v-row justify="center">
            <v-col cols="10">
                <inertia-link class="no-underline" :href="route('login')">
                    <v-btn color="secondary" text>
                        Cancel
                    </v-btn>
                </inertia-link>

                <v-btn
                    class="mx-4"
                    color="primary darken-2"
                    :disabled="!valid"
                    @click="$emit('next')"
                >
                    {{ editing ? "Submit" : "Next" }}
                </v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>

<script>
import states from "../../../../data/states";
import FormValidation from "../../../mixins/FormValidation";

export default {
    name: "companyInfo",
    props: {
        companyInfo: {
            type: Object
        },
        editing: {
            type: Boolean,
            default: false
        }
    },
    mixins: [FormValidation],
    data() {
        return {
            valid: false
        };
    },
    methods: {
        requireWithMaxLength(field, length) {
            return [
                val => !!val || `${field} is required.`,
                val =>
                    (val || "").length <= length ||
                    `${field} must be ${length} characters or less`
            ];
        }
    },
    computed: {
        statesObj() {
            return Object.entries(states).map(([key, value]) => ({
                id: key,
                name: value
            }));
        }
    },
    watch: {
        valid: function(newValue) {
            this.$emit("valid", newValue);
        }
    }
};
</script>
