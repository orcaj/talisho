<template>
    <v-form v-model="valid" id="user">
        <v-row justify="center">
            <v-col cols="12">
                <v-text-field
                    v-model="userInfo.first_name"
                    class="pb-2"
                    outlined
                    dense
                    :rules="requireWithMaxLength('First Name', 255)"
                    label="First Name"
                    placeholder="First Name"
                    :error="hasValidationError('userInfo.first_name')"
                    :error-messages="getValidationError('userInfo.first_name')"
                ></v-text-field>

                <v-text-field
                    v-model="userInfo.last_name"
                    class="pb-2"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Last Name', 255)"
                    label="Last Name"
                    placeholder="Last Name"
                    :error="hasValidationError('userInfo.last_name')"
                    :error-messages="getValidationError('userInfo.last_name')"
                ></v-text-field>

                <v-text-field
                    v-model="userInfo.email"
                    class="pb-2"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Email', 255)"
                    label="Email"
                    placeholder="Email"
                    :error="hasValidationError('userInfo.email')"
                    :error-messages="getValidationError('userInfo.email')"
                ></v-text-field>
                <v-text-field
                    v-model="userInfo.phone"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Cell Phone Number', 255)"
                    label="Cell Phone Number"
                    placeholder="Cell Phone Number"
                    :error="hasValidationError('userInfo.phone')"
                    :error-messages="getValidationError('userInfo.phone')"
                ></v-text-field>
                <v-text-field
                    v-model="userInfo.password"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Password', 255)"
                    label="Password"
                    placeholder="Password"
                    type="password"
                    :error="hasValidationError('userInfo.password')"
                    :error-messages="getValidationError('userInfo.password')"
                ></v-text-field>

                <v-text-field
                    v-model="userInfo.password_confirmation"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Re-Enter Password', 255)"
                    label="Re-Enter Password"
                    placeholder="Re-Enter Password"
                    type="password"
                ></v-text-field>
            </v-col>
        </v-row>

        <v-row justify="center">
            <v-col cols="10">
                <v-btn color="secondary" text @click="$emit('back')">
                    Cancel
                </v-btn>

                <v-btn
                    class="mx-4"
                    color="primary darken-2"
                    :disabled="!valid || !confirmVaild"
                    @click="$emit('next')"
                >
                    Payment Vaildation
                </v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>

<script>
import FormValidation from "../../../mixins/FormValidation";

export default {
    name: "userInfo",
    props: {
        userInfo: {
            type: Object
        }
    },
    mixins: [FormValidation],
    data() {
        return {
            valid: false,
        };
    },
    methods: {
        requireWithMaxLength(field, length) {
            return [
                val => !!val || `${field} is required.`,
                val =>
                    (val || "").length <= length ||
                    `${field} must be ${length} characters or less`,
            ];
        },
       
    },
    computed: {
         confirmVaild: function() {
            return (this.userInfo.password === this.userInfo.password_confirmation) && (this.userInfo.password.length >= 8);
        }
    },
    watch: {
        valid: function(newValue) {
            this.$emit("valid", newValue);
        }
    }
};
</script>
